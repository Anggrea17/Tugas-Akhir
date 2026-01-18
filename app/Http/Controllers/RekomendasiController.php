<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mpasi;
use Carbon\Carbon;

class RekomendasiController extends Controller
{
    public function hasil(Request $request)
    {
        $user = Auth::user();
        $bayi = $user->bayis()->find($request->bayi_id);

        if (!$bayi) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bayi tidak ditemukan.'
                ]);
            }
            return redirect()->route('landingpg')->withErrors(['bayi' => 'Bayi tidak ditemukan.']);
        }

        // mengecek kelengkapan data bayi
        $dataKurang = !$bayi->berat || !$bayi->tinggi || (
            !$bayi->volume_asi &&
            !($bayi->kalori_per_porsi && $bayi->jumlah_porsi_per_hari)
        );

        if ($dataKurang) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'lengkap' => false,
                    'bayi_id' => $bayi->id,
                    'bayi' => [
                        'nama_bayi' => $bayi->nama_bayi,
                        'tanggal_lahir' => $bayi->tanggal_lahir,
                        'jenis_kelamin' => $bayi->jenis_kelamin,
                        'umur' => $bayi->umur ?? null,
                        'berat' => $bayi->berat,
                        'tinggi' => $bayi->tinggi,
                        'volume_asi' => $bayi->volume_asi,
                        'kalori_per_porsi' => $bayi->kalori_per_porsi,
                        'jumlah_porsi_per_hari' => $bayi->jumlah_porsi_per_hari,
                    ]
                ]);
            }
            return redirect()->route('landingpg')
                ->with('selectedBayiId', $bayi->id)
                ->withErrors(['bayi' => 'Lengkapi data bayi terlebih dahulu.']);
        }

        //  Kalau data lengkap =>kalau AJAX, kirim redirect
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'lengkap' => true,
                'redirect' => route('rekomendasi', ['bayi_id' => $bayi->id])
            ]);
        }

        // mengambil data bayi
        $tanggal_lahir = $bayi->tanggal_lahir;
        $usia = Carbon::parse($tanggal_lahir)->diffInMonths(Carbon::now());
        $berat = $bayi->berat;
        $tinggi = $bayi->tinggi;

        // validasi usia
        if ($usia < 6 || $usia > 12) {
            return redirect()->back()->withErrors(['usia' => 'Rekomendasi hanya untuk bayi usia 6–12 bulan.']);
        }

        // 🔹 Hitung EER (Estimated Energy Requirement)
        if ($usia >= 7) {
            $eer = round(89 * $berat - 100) + 22;
        } else {
            $eer = round(89 * $berat - 100) + 56;
        }

        // 🔹 Hitung Kalori dari Susu (ASI / Sufor / Campuran)
        $kalori_asi = 0;
        $kalori_sufor = 0;

        if ($bayi->jenis_susu === 'ASI') {
            $kalori_asi = round($bayi->volume_asi * 0.67);
        } elseif ($bayi->jenis_susu === 'Sufor') {
            $kalori_sufor = round($bayi->kalori_per_porsi * $bayi->jumlah_porsi_per_hari);
        } elseif ($bayi->jenis_susu === 'Mix') {
            // Gabungan ASI + Sufor
            $kalori_asi = $bayi->volume_asi ? round($bayi->volume_asi * 0.67) : 0;
            $kalori_sufor = ($bayi->kalori_per_porsi && $bayi->jumlah_porsi_per_hari)
                ? round($bayi->kalori_per_porsi * $bayi->jumlah_porsi_per_hari)
                : 0;
        }

        $total_kalori_susu = $kalori_asi + $kalori_sufor;
        $kalori_mpasi = max(0, $eer - $total_kalori_susu);

// 🔹 Penyesuaian proporsi MPASI berdasarkan usia
if ($usia >= 6 && $usia <= 8) {
    $minimal_mpasi = round($eer * 0.30);
} elseif ($usia >= 9 && $usia <= 11) {
    $minimal_mpasi = round($eer * 0.40);
} else {
    $minimal_mpasi = round($eer * 0.50);
}

$kalori_mpasi = max($kalori_mpasi, $minimal_mpasi);

        // 🔹 Distribusi kalori MPASI per waktu makan
        $pagi  = round($kalori_mpasi * 0.25);
        $siang = round($kalori_mpasi * 0.30);
        $malam = round($kalori_mpasi * 0.25);
        $snack = round($kalori_mpasi * 0.20);

        $selectedRecipeIds = [];

// 🔹 Tentukan range ±20% untuk setiap waktu makan
$range = function($kalori){
    return [
        'min' => round($kalori * 0.8),
        'max' => round($kalori * 1.2),
    ];
};

$rangePagi  = $range($pagi);
$rangeSiang = $range($siang);
$rangeMalam = $range($malam);
$rangeSnack = $range($snack);

// mengambil resep unik dengan batas ±20%
$getUniqueRecipes = function(
    $targetRange,
    &$selectedIds,
    $age,
    $limit = 1,
    $poolSize = 15,
    $kategoriId = null,
    $excludeCamilan = false
) {
    // Hitung target kalori dari range
    $targetCal = round(($targetRange['min'] + $targetRange['max']) / 2);

    $baseQuery = Mpasi::with(['bahans', 'langkahs'])
        ->where('min_umur', '<=', $age)
        ->where('max_umur', '>=', $age);

    if ($kategoriId) {
        $baseQuery->where('kategori_id', $kategoriId);
    }
    if ($excludeCamilan) {
        $baseQuery->where('kategori_id', '!=', 3);
    }

    // 1) PRIORITAS PERTAMA => Cari energi terdekat dengan target
   $closest = (clone $baseQuery)
    ->whereNotIn('id', $selectedIds)
    ->orderByRaw("ABS(energi - {$targetCal}) ASC")
    ->take(5) // ambil 5 terdekat
    ->get();

if ($closest->isNotEmpty()) {
    $recipe = $closest->random(); // random 1 dari 5
    $selectedIds[] = $recipe->id;
    return collect([$recipe]);
}


    // 2) PRIORITAS KEDUA => Jika tidak ada, cari dalam range ±20%
    $candidates = (clone $baseQuery)
        ->whereBetween('energi', [$targetRange['min'], $targetRange['max']])
        ->whereNotIn('id', $selectedIds)
        ->take($poolSize)
        ->get();

    if ($candidates->isNotEmpty()) {
        $recipe = $candidates->random();
        $selectedIds[] = $recipe->id;
        return collect([$recipe]);
    }

    // 3️) PRIORITAS KETIGA => Jika ±20% juga tidak ada, ambil energi di atas target terdekat
    $fallback = (clone $baseQuery)
        ->where('energi', '>=', $targetCal)
        ->whereNotIn('id', $selectedIds)
        ->orderBy('energi', 'asc')
        ->take(1)
        ->get();

    if ($fallback->isNotEmpty()) {
        $recipe = $fallback->first();
        $selectedIds[] = $recipe->id;
        return collect([$recipe]);
    }

    return collect();
};


// Ambil resep per waktu makan
$resepPagi  = $getUniqueRecipes($rangePagi, $selectedRecipeIds, $usia, 1, 15, null, true);
$resepSiang = $getUniqueRecipes($rangeSiang, $selectedRecipeIds, $usia, 1, 15, null, true);
$resepMalam = $getUniqueRecipes($rangeMalam, $selectedRecipeIds, $usia, 1, 15, null, true);
$resepSnack = $getUniqueRecipes($rangeSnack, $selectedRecipeIds, $usia, 1, 15, 3);

// 🔹 Format angka supaya konsisten
$berat = (int) $bayi->berat == $bayi->berat ? number_format($bayi->berat, 0, '.', '') : number_format($bayi->berat, 1, '.', '');
$tinggi = (int) $bayi->tinggi == $bayi->tinggi ? number_format($bayi->tinggi, 0, '.', '') : number_format($bayi->tinggi, 1, '.', '');
// 🔹 Format volume ASI
if (!is_null($bayi->volume_asi)) {
    $volume_asi = (fmod($bayi->volume_asi, 1) == 0)
        ? number_format($bayi->volume_asi, 0, '.', '')
        : number_format($bayi->volume_asi, 1, '.', '');
} else {
    $volume_asi = '-';
}

// 🔹 Format tanggal lahir (contoh: 26 Oktober 2025)
$tanggal_lahir = Carbon::parse($bayi->tanggal_lahir)->translatedFormat('d F Y');
// format kalori_per_porsi
if (!is_null($bayi->kalori_per_porsi)) {
    $kalori_per_porsi = (int) $bayi->kalori_per_porsi == $bayi->kalori_per_porsi
        ? number_format($bayi->kalori_per_porsi, 0, '.', '')
        : number_format($bayi->kalori_per_porsi, 1, '.', '');
} else {
    $kalori_per_porsi = '-';
}

        return response()
            ->view('hsrekomen', compact(
                'bayi', 'user', 'usia', 'berat', 'tinggi','volume_asi',
                'eer', 'kalori_asi', 'kalori_sufor', 'kalori_mpasi','kalori_per_porsi',
                'pagi', 'siang', 'malam', 'snack',
                'resepPagi', 'resepSiang', 'resepMalam', 'resepSnack', 'tanggal_lahir'
            ))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
