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
        Schema::table('nilais', function (Blueprint $table) {
            $table->string('nilai_huruf')->after('nilai')->nullable();
            $table->string('predikat')->after('nilai_huruf')->nullable();
            $table->string('keterangan')->after('predikat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            $table->dropColumn('nilai_huruf');
            $table->dropColumn('predikat');
            $table->dropColumn('keterangan');
        });
    }
};
