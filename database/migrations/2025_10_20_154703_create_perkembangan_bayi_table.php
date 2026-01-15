<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perkembangan_bayi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bayi_id')
                  ->constrained('bayis')
                  ->onDelete('cascade'); // kalau bayi dihapus, datanya ikut terhapus

            $table->integer('umur_bulan'); // umur bayi dalam bulan saat data dicatat
            $table->float('berat')->nullable(); // berat badan bayi (kg)
            $table->float('tinggi')->nullable(); // tinggi badan bayi (cm)
            $table->date('tanggal_catat')->default(now()); // tanggal pencatatan otomatis hari ini
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perkembangan_bayi');
    }
};
