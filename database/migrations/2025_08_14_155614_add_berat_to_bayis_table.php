<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            $table->decimal('berat', 5, 2)->after('tanggal_lahir')->nullable();
            // 5 total digit, 2 angka di belakang koma, contoh: 8.50
        });
    }

    public function down(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            $table->dropColumn('berat');
        });
    }
};
