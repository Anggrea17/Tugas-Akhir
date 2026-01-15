<?php

namespace App\Http\Controllers;

use App\Models\Bayi;
use App\Models\PerkembanganBayi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PerkembanganBayiController extends Controller
{
    /**
     * Menampilkan grafik pertumbuhan bayi
     */
public function index(Request $request)
{
    $user = Auth::user();

    // Ambil semua bayi milik user login
    $bayis = $user->bayis()->get();

    if ($bayis->isEmpty()) {
        return view('perkembanganbayi', [
            'bayis' => $bayis,
            'selectedBayi' => null,
            'perkembangan' => collect(),
            'dataPerkembangan' => [],
        ]);
    }

    // Pilih bayi aktif dari parameter (kalau ada)
    $selectedBayiId = $request->get('bayi_id') ?? $bayis->first()->id;
    $selectedBayi = $bayis->firstWhere('id', $selectedBayiId);

    // Ambil semua perkembangan bayi milik user
    $allPerkembangan = PerkembanganBayi::whereIn('bayi_id', $bayis->pluck('id'))
        ->orderBy('tanggal_catat', 'asc')
        ->get(['bayi_id', 'tanggal_catat', 'berat', 'tinggi']);

    // Format angka agar tidak tampil .00
    $allPerkembangan->transform(function ($item) {
        $item->berat = $this->formatNumber($item->berat);
        $item->tinggi = $this->formatNumber($item->tinggi);
        return $item;
    });

    // Kelompokkan data per bayi untuk chart
    $dataPerkembangan = $allPerkembangan->groupBy('bayi_id')->map(function ($items) {
        return $items->map(function ($item) {
            return [
                'tanggal_catat' => $item->tanggal_catat,
                'berat' => $item->berat,
                'tinggi' => $item->tinggi,
            ];
        })->values()->toArray();
    })->toArray();

    // Data perkembangan untuk bayi terpilih
    $perkembangan = $allPerkembangan->where('bayi_id', $selectedBayiId)->values();

    return view('perkembanganbayi', [
        'bayis' => $bayis,
        'selectedBayi' => $selectedBayi,
        'perkembangan' => $perkembangan,
        'dataPerkembangan' => $dataPerkembangan,
    ]);
}

/**
 * Helper untuk memformat angka agar tidak menampilkan .00
 */
private function formatNumber($value)
{
    if ($value === null) return '-';
    $num = floatval($value);
    return fmod($num, 1) == 0
        ? number_format($num, 0, '.', '')
        : number_format($num, 1, '.', '');
}


//**hapus catatan perkembangan tertentu */
    public function destroy($id)
    {
        $user = Auth::user();

        // Cari catatan perkembangan berdasarkan ID dan pastikan milik bayi user
        $perkembangan = PerkembanganBayi::find($id);

        if (!$perkembangan) {
            return redirect()->back()->with('error', 'Catatan perkembangan tidak ditemukan.');
        }

        $bayi = Bayi::where('id', $perkembangan->bayi_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$bayi) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus catatan ini.');
        }

        // Hapus catatan perkembangan
        $perkembangan->delete();

        return redirect()->back()->with('success', 'Catatan perkembangan berhasil dihapus.');
    }

    /**
     * Mendapatkan data perkembangan bayi untuk chart (AJAX)
     */
    public function getData(Request $request, $userId, $bayiId)
    {
        $user = Auth::user();

        // Pastikan user hanya mengakses data dirinya sendiri
        if ($user->id != $userId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Ambil data perkembangan bayi
        $perkembangan = PerkembanganBayi::where('bayi_id', $bayiId)
            ->orderBy('tanggal_catat', 'asc')
            ->get(['tanggal_catat', 'berat', 'tinggi']);

        return response()->json($perkembangan);
    }

}