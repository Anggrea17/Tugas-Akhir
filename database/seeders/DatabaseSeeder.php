<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
 {   
User::create([
    'nama' => 'Eka Admin',
    'username' => 'ekaadmin',
    'no_hp' => '08123456789',
    'alamat' => 'Jl. Admin',
    'email' => 'eka.admin@example.com',
    'password' => Hash::make('12345678'),
    'role' => 'admin',
]);
}
public function run(): void
{
    $this->call([
        KategoriArtikelSeeder::class,
    ]);
}
public function run(): void
{
    $this->call([
        KategoriMpasiSeeder::class,
    ]);
}

}