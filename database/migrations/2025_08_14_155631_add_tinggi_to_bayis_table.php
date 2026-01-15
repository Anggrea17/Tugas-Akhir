<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            $table->float('tinggi')->nullable()->after('berat'); 
            // pakai float kalau cm bisa desimal, misal 52.5 cm
        });
    }

    public function down(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            $table->dropColumn('tinggi');
        });
    }
};
