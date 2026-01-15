<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriMpasi;
use App\Models\Mpasi;

class KategorimpasiSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kategori (jika belum ada)
        $bubur   = KategoriMpasi::firstOrCreate(['nama_kategori' => 'Bubur']);
        $nasi    = KategoriMpasi::firstOrCreate(['nama_kategori' => 'Nasi']);
        $camilan = KategoriMpasi::firstOrCreate(['nama_kategori' => 'Camilan']);

    }
}
