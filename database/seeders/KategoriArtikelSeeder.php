<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriArtikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori_artikel')->insert([
            ['nama_kategori' => 'Tips MPASI'],
            ['nama_kategori' => 'Alergi Tips'],
            ['nama_kategori' => 'Panduan'],
        ]);
    }
}
