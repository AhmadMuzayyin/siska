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
            $table->dropForeign(['jadwal_pelajaran_id']);

            // Rename kolom
            $table->renameColumn('jadwal_pelajaran_id', 'mapel_id');

            // Tambah foreign key baru
            $table->foreign('mapel_id')
                ->references('id')
                ->on('mapels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilais', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['mapel_id']);

            // Rename kolom kembali
            $table->renameColumn('mapel_id', 'jadwal_pelajaran_id');

            // Tambah foreign key lama
            $table->foreign('jadwal_pelajaran_id')
                ->references('id')
                ->on('jadwal_pelajarans')
                ->onDelete('cascade');
        });
    }
};
