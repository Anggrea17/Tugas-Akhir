<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bahan_mpasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mpasi_id')
                  ->constrained('mpasi')   // FK ke tabel mpasi
                  ->onDelete('cascade');  // kalau resep dihapus, bahannya ikut hilang
            $table->string('bahan');
            $table->string('takaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_mpasi');
    }
};
