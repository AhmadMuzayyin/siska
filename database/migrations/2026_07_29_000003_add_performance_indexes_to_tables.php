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
        Schema::table('santris', function (Blueprint $table) {
            $table->index(['lembaga_id', 'status'], 'idx_santris_lembaga_status');
            $table->index(['kelas_id', 'status'], 'idx_santris_kelas_status');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->index(['jadwal_pelajaran_id', 'tanggal'], 'idx_absensis_jadwal_tanggal');
            $table->index(['santri_id', 'tanggal'], 'idx_absensis_santri_tanggal');
        });

        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->index(['guru_id', 'semester_id', 'status', 'tanggal'], 'idx_absensi_gurus_search');
        });

        Schema::table('spps', function (Blueprint $table) {
            $table->index(['santri_id', 'bulan', 'tahun'], 'idx_spps_santri_bulan_tahun');
            $table->index('tanggal', 'idx_spps_tanggal');
        });

        Schema::table('nilais', function (Blueprint $table) {
            $table->index(['santri_id', 'semester_id', 'mapel_id'], 'idx_nilais_santri_semester_mapel');
        });

        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            $table->index(['semester_id', 'kelas_id', 'hari'], 'idx_jadwal_pelajarans_search');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajarans', function (Blueprint $table) {
            $table->dropIndex('idx_jadwal_pelajarans_search');
        });

        Schema::table('nilais', function (Blueprint $table) {
            $table->dropIndex('idx_nilais_santri_semester_mapel');
        });

        Schema::table('spps', function (Blueprint $table) {
            $table->dropIndex('idx_spps_santri_bulan_tahun');
            $table->dropIndex('idx_spps_tanggal');
        });

        Schema::table('absensi_gurus', function (Blueprint $table) {
            $table->dropIndex('idx_absensi_gurus_search');
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->dropIndex('idx_absensis_jadwal_tanggal');
            $table->dropIndex('idx_absensis_santri_tanggal');
        });

        Schema::table('santris', function (Blueprint $table) {
            $table->dropIndex('idx_santris_lembaga_status');
            $table->dropIndex('idx_santris_kelas_status');
        });
    }
};
