<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('mpasi', function (Blueprint $table) {
        $table->decimal('karbohidrat', 8, 2)->nullable()->change();
        $table->decimal('protein', 8, 2)->nullable()->change();
        $table->decimal('lemak', 8, 2)->nullable()->change();
        $table->decimal('zat_besi', 8, 2)->nullable()->change();
    });
}

public function down()
{
    Schema::table('mpasi', function (Blueprint $table) {
        $table->decimal('karbohidrat', 8, 2)->change();
        $table->decimal('protein', 8, 2)->change();
        $table->decimal('lemak', 8, 2)->change();
        $table->decimal('zat_besi', 8, 2)->change();
    });
}
};
