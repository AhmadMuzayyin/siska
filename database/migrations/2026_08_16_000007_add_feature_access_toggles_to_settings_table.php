<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('is_input_nilai_open')->default(true)->after('fitur_pesan_whatsapp');
            $table->boolean('is_ppdb_open')->default(true)->after('is_input_nilai_open');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['is_input_nilai_open', 'is_ppdb_open']);
        });
    }
};
