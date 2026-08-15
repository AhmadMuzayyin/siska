<?php

use App\Models\Kelas;
use App\Models\Mapel;
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
        if (! Schema::hasColumn('kelas', 'kode')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }

        if (! Schema::hasColumn('mapels', 'kode')) {
            Schema::table('mapels', function (Blueprint $table) {
                $table->string('kode')->nullable()->unique()->after('id');
            });
        }

        // Populate existing records with unique codes
        foreach (Kelas::all() as $index => $kelas) {
            if (empty($kelas->kode)) {
                $kelas->update([
                    'kode' => 'KLS-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                ]);
            }
        }

        foreach (Mapel::all() as $index => $mapel) {
            if (empty($mapel->kode)) {
                $mapel->update([
                    'kode' => 'MPL-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('kelas', 'kode')) {
            Schema::table('kelas', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }

        if (Schema::hasColumn('mapels', 'kode')) {
            Schema::table('mapels', function (Blueprint $table) {
                $table->dropColumn('kode');
            });
        }
    }
};
