<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BbUReferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bb_u_referensi')->insert([
            ['gender'=>'P','usia_bulan'=>6,'bb_min'=>5.7,'bb_max'=>9.3],
            ['gender'=>'P','usia_bulan'=>7,'bb_min'=>6.0,'bb_max'=>9.8],
            ['gender'=>'P','usia_bulan'=>8,'bb_min'=>6.3,'bb_max'=>10.2],
            ['gender'=>'P','usia_bulan'=>9,'bb_min'=>6.5,'bb_max'=>10.5],
            ['gender'=>'P','usia_bulan'=>10,'bb_min'=>6.7,'bb_max'=>10.9],
            ['gender'=>'P','usia_bulan'=>11,'bb_min'=>6.9,'bb_max'=>11.2],
            ['gender'=>'P','usia_bulan'=>12,'bb_min'=>7.0,'bb_max'=>11.5],
            ['gender'=>'L','usia_bulan'=>6,'bb_min'=>6.4,'bb_max'=>9.8],
            ['gender'=>'L','usia_bulan'=>7,'bb_min'=>6.7,'bb_max'=>10.3],
            ['gender'=>'L','usia_bulan'=>8,'bb_min'=>6.9,'bb_max'=>10.7],
            ['gender'=>'L','usia_bulan'=>9,'bb_min'=>7.1,'bb_max'=>11.0],
            ['gender'=>'L','usia_bulan'=>10,'bb_min'=>7.4,'bb_max'=>11.4],
            ['gender'=>'L','usia_bulan'=>11,'bb_min'=>7.6,'bb_max'=>11.7],
            ['gender'=>'L','usia_bulan'=>12,'bb_min'=>7.7,'bb_max'=>12.0],
        ]);
        
    }
}
