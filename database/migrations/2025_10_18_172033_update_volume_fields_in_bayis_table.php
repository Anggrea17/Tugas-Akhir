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
    Schema::table('bayis', function (Blueprint $table) {
        $table->dropColumn('volume_susu');
        $table->float('volume_asi')->nullable()->after('tinggi');
        $table->float('volume_sufor')->nullable()->after('volume_asi');
    });
}

public function down()
{
    Schema::table('bayis', function (Blueprint $table) {
        $table->dropColumn(['volume_asi', 'volume_sufor']);
        $table->float('volume_susu')->nullable()->after('tinggi');
    });
}

};
