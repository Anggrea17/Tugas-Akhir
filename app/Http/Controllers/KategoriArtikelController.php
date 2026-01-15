<?php

namespace App\Http\Controllers;

use App\Models\KategoriArtikel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriArtikelController extends Controller
{
    public function kelolakategoriartikel(Request $request)
    {
        $search = $request->input('search');

        $query = KategoriArtikel::query();
        
        if ($search) {
            $query->where('nama_kategori', 'like', "%{$search}%");
        }

        $kategoris = $query->latest()->paginate(10);
        
        return view('admin.kelolakategoriartikel', compact('kategoris', 'search'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_artikel,nama_kategori',],
            [
                 'nama_kategori.unique' => 'Nama kategori artikel sudah digunakan, silahkan pakai nama lain.',
        'nama_kategori.required' => 'Nama kategori artikel tidak boleh kosong.',
        'nama_kategori.max' => 'Nama kategori artikel tidak boleh lebih dari 50.',
        ]);

        KategoriArtikel::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategoriartikel')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(KategoriArtikel $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, KategoriArtikel $kategori)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:50|unique:kategori_artikel,nama_kategori,' . $kategori->id,],
            [
                 'nama_kategori.unique' => 'Nama kategori artikel sudah digunakan oleh kategori lain.',
        'nama_kategori.required' => 'Nama kategori artikel tidak boleh kosong.',
        'nama_kategori.max' => 'Nama kategori artikel tidak boleh lebih dari 50.',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategoriartikel')->with('success', 'Kategori berhasil diperbarui!');
    }

public function destroy(KategoriArtikel $kategori)
{
    if ($kategori->artikel()->count() > 0) {
        return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki artikel.');
    }

    $kategori->delete();
    return back()->with('success', 'Kategori berhasil dihapus!');
}

}