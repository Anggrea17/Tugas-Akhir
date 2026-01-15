<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            // Rename kolom volume_asi -> volume_susu
            if (Schema::hasColumn('bayis', 'volume_asi')) {
                $table->renameColumn('volume_asi', 'volume_susu');
            }
        });

        Schema::table('bayis', function (Blueprint $table) {
            // Tambah kolom jenis susu
            $table->enum('jenis_susu', ['ASI', 'Sufor', 'Mix']) ->nullable()
                ->default('ASI')
                ->after('tanggal_lahir');

            // Tambah kolom kalori & volume susu formula
            $table->decimal('kalori_per_porsi', 6, 2)
                ->nullable()
                ->after('volume_susu');

            $table->integer('volume_per_porsi')
                ->nullable()
                ->after('kalori_per_porsi');

            $table->integer('jumlah_porsi_per_hari')
                ->nullable()
                ->after('volume_per_porsi');
        });
    }

    public function down(): void
    {
        Schema::table('bayis', function (Blueprint $table) {
            // Kembalikan ke semula
            $table->renameColumn('volume_susu', 'volume_asi');
            $table->dropColumn([
                'jenis_susu',
                'kalori_per_porsi',
                'volume_per_porsi',
                'jumlah_porsi_per_hari'
            ]);
        });
    }
};
