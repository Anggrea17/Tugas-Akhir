<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel kategori artikel
        Schema::create('kategori_artikel', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        // Tambahkan kolom kategori_id di tabel artikel
        Schema::table('artikel', function (Blueprint $table) {
            $table->unsignedBigInteger('kategori_id')->nullable()->after('id');

            // Foreign key (hapus cascade biar kalau kategori dihapus, artikelnya ikut dihapus atau set null)
            $table->foreign('kategori_id')
                  ->references('id')
                  ->on('kategori_artikel')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Hapus foreign key & kolom dari artikel
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        // Hapus tabel kategori_artikel
        Schema::dropIfExists('kategori_artikel');
    }
};
