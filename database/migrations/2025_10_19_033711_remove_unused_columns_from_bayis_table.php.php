<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            if (Schema::hasColumn('bayis', 'volume_sufor')) {
                $table->dropColumn('volume_sufor');
            }

            if (Schema::hasColumn('bayis', 'volume_per_porsi')) {
                $table->dropColumn('volume_per_porsi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            // Tambahkan kembali jika rollback
            if (!Schema::hasColumn('bayis', 'volume_sufor')) {
                $table->integer('volume_sufor')->nullable()->after('volume_asi');
            }

            if (!Schema::hasColumn('bayis', 'volume_per_porsi')) {
                $table->integer('volume_per_porsi')->nullable()->after('kalori_per_porsi');
            }
        });
    }
};
