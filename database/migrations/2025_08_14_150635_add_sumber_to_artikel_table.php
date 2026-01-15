<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('artikel', function (Blueprint $table) {
        $table->string('sumber')->nullable()->after('isi'); 
    });
}

public function down(): void
{
    Schema::table('artikel', function (Blueprint $table) {
        $table->dropColumn('sumber');
    });
}

};
