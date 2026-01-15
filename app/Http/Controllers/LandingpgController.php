<?php

namespace App\Http\Controllers;

use App\Models\Mpasi;
use App\Models\Artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingpgController extends Controller
{
    public function index()
    {
        // Ambil data MPASI (limit 4)
        $mpasi = Mpasi::select('nama_menu', 'gambar')->take(4)->get();

        // Ambil artikel terbaru (limit 3)
        $artikels = Artikel::latest()->take(3)->get();

        // Ambil user login
        $user = Auth::user(); 

        // Ambil bayi milik user, pastikan selalu dalam bentuk collection
        $bayis = $user ? $user->bayis()->get() : collect();

        // Ambil bayi terpilih (kalau user tadi gagal ke halaman rekomendasi karena datanya kurang lengkap)
        $selectedBayiId = session('selectedBayiId');

        return view('landingpg', [
            'artikels' => $artikels,
            'mpasi' => $mpasi,
            'bayis' => $bayis,
            'selectedBayiId' => $selectedBayiId, // penting buat preselect modal
        ]);
    }
}
