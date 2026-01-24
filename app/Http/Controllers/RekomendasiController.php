<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Mpasi;
use App\Models\BbUReferensi;
use Carbon\Carbon;

class RekomendasiController extends Controller
{
    public function hasil(Request $request)
    {
        $user = Auth::user();
        $bayi = $user->bayis()->find($request->bayi_id);

        if (!$bayi) {
            return redirect()->route('landingpg')
                ->withErrors(['bayi' => 'Bayi tidak ditemukan.']);
        }

        // 🔹 Validasi kelengkapan data
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

        // ✅ Kalau data lengkap → kalau AJAX, kirim redirect
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'lengkap' => true,
                'redirect' => route('rekomendasi', ['bayi_id' => $bayi->id])
            ]);
        }

        // 🔹 Hitung usia
        $usia = Carbon::parse($bayi->tanggal_lahir)->diffInMonths(Carbon::now());
        if ($usia < 6 || $usia > 12) {
            return redirect()->back()
                ->withErrors(['usia' => 'Rekomendasi hanya untuk bayi usia 6–12 bulan.']);
        }

        $berat  = $bayi->berat;
        $tinggi = $bayi->tinggi;

        // =========================================================
        // 🔹 CEK STATUS PERTUMBUHAN (BB/U WHO)
        // =========================================================
        $gender = $bayi->jenis_kelamin === 'Laki-laki' ? 'L' : 'P';

        $referensi = BbUReferensi::where('usia_bulan', $usia)
            ->where('gender', $gender)
            ->first();

        $status_pertumbuhan = 'Data referensi tidak tersedia';

        if ($referensi) {
            if ($berat < $referensi->bb_min) {
                $status_pertumbuhan = 'Berat badan kurang';
            } elseif ($berat > $referensi->bb_max) {
                $status_pertumbuhan = 'Berat badan berlebih';
            } else {
                $status_pertumbuhan = 'Pertumbuhan dalam rentang wajar';
            }
        }

        // =========================================================
        // 🔹 TENTUKAN BERAT UNTUK PERHITUNGAN ENERGI
        // =========================================================
        $berat_perhitungan = $berat;

        if ($status_pertumbuhan === 'Berat badan berlebih' && $referensi) {
            // pakai BB batas atas WHO (BUKAN BB aktual)
            $berat_perhitungan = $referensi->bb_max;
        }

        // =========================================================
        // 🔹 HITUNG EER (ENERGY REQUIREMENT)
        // =========================================================
        if ($usia >= 7) {
            $eer = round(89 * $berat_perhitungan - 100) + 22;
        } else {
            $eer = round(89 * $berat_perhitungan - 100) + 56;
        }
        //=================================================================
        // 🔹 BATAS AMAN MINIMAL MPASI ABSOLUT BERDASARKAN USIA (FAILSAFE)
        //=================================================================
        if ($usia <= 8) {
            $minimal_mpasi_absolut = 200;
        } elseif ($usia <= 11) {
            $minimal_mpasi_absolut = 300;
        } else {
            $minimal_mpasi_absolut = 550;
        }


        // =========================================================
        // 🔹 HITUNG KALORI DARI SUSU
        // =========================================================
        $kalori_asi   = 0;
        $kalori_sufor = 0;

        if ($bayi->jenis_susu === 'ASI') {
            $kalori_asi = round($bayi->volume_asi * 0.67);
        } elseif ($bayi->jenis_susu === 'Sufor') {
            $kalori_sufor = round($bayi->kalori_per_porsi * $bayi->jumlah_porsi_per_hari);
        } elseif ($bayi->jenis_susu === 'Mix') {
            $kalori_asi = $bayi->volume_asi
                ? round($bayi->volume_asi * 0.67)
                : 0;

            $kalori_sufor = ($bayi->kalori_per_porsi && $bayi->jumlah_porsi_per_hari)
                ? round($bayi->kalori_per_porsi * $bayi->jumlah_porsi_per_hari)
                : 0;
        }

        $total_kalori_susu = $kalori_asi + $kalori_sufor;

        // =========================================================
        // 🔹 KALORI MINIMAL MPASI BERDASARKAN USIA
        // =========================================================
        if ($usia <= 8) {
            $minimal_mpasi = round($eer * 0.30);
        } elseif ($usia <= 11) {
            $minimal_mpasi = round($eer * 0.40);
        } else {
            $minimal_mpasi = round($eer * 0.50);
        }

        // =========================================================
        // 🔹 HITUNG KALORI MPASI
        // =========================================================
        if ($status_pertumbuhan === 'Berat badan berlebih') {
            $kalori_mpasi = max(
                round($minimal_mpasi * 0.8),
                $minimal_mpasi_absolut
            );
        } else {
            $kalori_mpasi = max(
                $eer - $total_kalori_susu,
                $minimal_mpasi,
                $minimal_mpasi_absolut
            );
        }
        

        // =========================================================
        // 🔹 DISTRIBUSI MPASI
        // =========================================================
        $pagi  = round($kalori_mpasi * 0.25);
        $siang = round($kalori_mpasi * 0.30);
        $malam = round($kalori_mpasi * 0.25);
        $snack = round($kalori_mpasi * 0.20);

        // =========================================================
        // 🔹 AMBIL RESEP (UNIK + ±20%)
        // =========================================================
        $selectedRecipeIds = [];

        $range = fn($k) => [
            'min' => round($k * 0.8),
            'max' => round($k * 1.2),
        ];

        $getRecipes = function ($range, &$used, $usia, $kategori = null, $excludeSnack = false) {

            $q = Mpasi::where('min_umur', '<=', $usia)
                ->where('max_umur', '>=', $usia)
                ->whereBetween('energi', [$range['min'], $range['max']])
                ->whereNotIn('id', $used);
        
            if ($kategori) {
                $q->where('kategori_id', $kategori);
            }
        
            if ($excludeSnack) {
                $q->where('kategori_id', '!=', 3);
            }
        
            // 🔹 ambil random dari range
            $resep = $q->inRandomOrder()->first();
        
            if ($resep) {
                $used[] = $resep->id;
                return collect([$resep]);
            }
        
            return collect();
        };
        

        $resepPagi  = $getRecipes($range($pagi),  $selectedRecipeIds, $usia, null, true);
        $resepSiang = $getRecipes($range($siang), $selectedRecipeIds, $usia, null, true);
        $resepMalam = $getRecipes($range($malam), $selectedRecipeIds, $usia, null, true);
        $resepSnack = $getRecipes($range($snack), $selectedRecipeIds, $usia, 3);

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
                'resepPagi', 'resepSiang', 'resepMalam', 'resepSnack', 'tanggal_lahir','status_pertumbuhan'
            ))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
