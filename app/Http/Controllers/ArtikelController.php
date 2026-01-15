<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Artikel;
use App\Models\KategoriArtikel;

class ArtikelController extends Controller
{
public function artikel(Request $request)
{
    $search = $request->input('search');
    $kategoriFilter = $request->input('kategori'); 
    $query = Artikel::query();
    $bayis = auth()->check() ? auth()->user()->bayis : [];      

    if ($search) {
        $query->where('judul', 'like', "%{$search}%")
              ->orWhere('isi', 'like', "%{$search}%"); 
    }

    if ($kategoriFilter) {
        $query->where('kategori_id', $kategoriFilter);
    }

    $categories = KategoriArtikel::pluck('nama_kategori', 'id'); 

    $artikels = $query->orderBy('created_at', 'desc')->paginate(5);

    // Ambil 3 artikel lain sebagai "related articles"
    $relatedArtikels = Artikel::where('id', '!=', optional($artikels->first())->id)
        ->when($kategoriFilter, function ($q) use ($kategoriFilter) {
            $q->where('kategori_id', $kategoriFilter);
        })
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

    return view('artikel', compact('artikels', 'categories', 'search', 'kategoriFilter', 'bayis', 'relatedArtikels'));
}


    public function show($slug)
    {
    $artikel = Artikel::where('slug', $slug)->firstOrFail();
       // Ambil 3 artikel terbaru lainnya sebagai rekomendasi
        $relatedArtikels = Artikel::latest()->where('slug', '!=', $artikel->slug)->take(3)->get();
    return view('detailartikel', compact('artikel', 'relatedArtikels'));
    }


}


