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
        Schema::table('mpasi', function (Blueprint $table) {
            $table->dropColumn(['bahan', 'langkah']);
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::table('mpasi', function (Blueprint $table) {
            $table->text('bahan')->nullable();
            $table->text('langkah')->nullable();
        });
    }
};
