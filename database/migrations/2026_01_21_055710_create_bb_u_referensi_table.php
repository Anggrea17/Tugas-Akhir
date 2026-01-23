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
        Schema::create('bb_u_referensi', function (Blueprint $table) {
            $table->id();
            $table->enum('gender', ['L', 'P']);
            $table->integer('usia_bulan');
            $table->decimal('bb_min', 4, 1); // SD2neg
            $table->decimal('bb_max', 4, 1); // SD2
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bb_u_referensi');
    }
};
