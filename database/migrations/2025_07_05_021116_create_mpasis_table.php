<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('mpasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->string('bahan');
            $table->integer('min_umur');
            $table->integer('max_umur');
            $table->float('energi');
            $table->float('karbohidrat');
            $table->float('protein');
            $table->float('lemak');
            $table->float('zat_besi');
            $table->string('kategori');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('mpasi');
    }
};