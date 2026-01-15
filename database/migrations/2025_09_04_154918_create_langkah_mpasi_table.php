<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('langkah_mpasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mpasi_id')
                  ->constrained('mpasi')
                  ->onDelete('cascade');
            $table->integer('urutan')->default(1);
            $table->text('langkah');
            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('langkah_mpasi');
    }
};
