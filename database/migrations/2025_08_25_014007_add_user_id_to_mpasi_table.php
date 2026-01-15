<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- WAJIB ditambah

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Tambahkan kolom user_id dengan default 1
        Schema::table('mpasi', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('id')->default(1);
        });

        // 2. Isi semua data lama user_id = 1
        DB::table('mpasi')->update(['user_id' => 1]);

        // 3. Baru tambahkan foreign key
        Schema::table('mpasi', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('mpasi', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
