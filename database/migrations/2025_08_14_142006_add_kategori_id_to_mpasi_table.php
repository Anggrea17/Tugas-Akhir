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
    Schema::table('mpasi', function (Blueprint $table) {
        $table->unsignedBigInteger('kategori_id')->nullable()->after('kategori');
        $table->foreign('kategori_id')->references('id')->on('kategori_mpasi')->onDelete('cascade');
    });
}

};
