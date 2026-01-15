<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriMpasi;

class KategoriMpasiController extends Controller
{
    /**
     * Tampilkan semua kategori MPASI
     */
    public function kelolakategorimpasi(Request $request)
    {
        $query = KategoriMpasi::query();

        // Filter / search
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_kategori', 'like', '%' . $request->search . '%');
        }

        $kategoris = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.kelolakategorimpasi', compact('kategoris'));
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
       $request->validate([
        'nama' => [
            'required',
            'string',
            'max:50',
            'unique:kategori_mpasi,nama_kategori'
        ],
    ], [
        'nama.unique' => 'Nama kategori resep sudah digunakan, silakan pakai nama lain',
        'nama.required' => 'Nama kategori resep tidak boleh kosong.',
        'nama.max' => 'Nama kategori resep tidak boleh lebih dari 50.',
    ]);

    KategoriMpasi::create([
        'nama_kategori' => $request->nama,
    ]);


        return redirect()->route('admin.kategorimpasi')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Update kategori
     */
    public function update(Request $request, KategoriMpasi $kategori)
    {
        $request->validate([
        'nama' => [
            'required',
            'string',
            'max:50',
            'unique:kategori_mpasi,nama_kategori,' . $kategori->id
        ],
    ], [
        'nama.unique' => 'Nama kategori resep sudah digunakan oleh kategori lain.',
        'nama.required' => 'Nama kategori resep tidak boleh kosong.',
        'nama.max' => 'Nama kategori resep tidak boleh lebih dari 50.',
    ]);

    $kategori->update([
        'nama_kategori' => $request->nama,
    ]);

        return redirect()->route('admin.kategorimpasi')
                         ->with('success', 'Kategori MPASI berhasil diperbarui.');
    }

    /**
     * Hapus kategori
     */
    public function destroy(KategoriMpasi $kategori)
    {
           if ($kategori->mpasi()->count() > 0) {
        return back()->with('error', 'Tidak bisa menghapus kategori yang masih memiliki resep.');
    }
        $kategori->delete();

        return redirect()->route('admin.kategorimpasi')
                         ->with('success', 'Kategori MPASI berhasil dihapus.');
    }
}
