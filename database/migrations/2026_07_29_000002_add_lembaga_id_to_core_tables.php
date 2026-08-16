<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Get existing setting or create default lembaga name
        $setting = DB::table('settings')->first();
        $lembagaNama = $setting?->lembaga ?? 'MDTA ARROQY';

        $defaultLembagaId = DB::table('lembagas')->insertGetId([
            'kode' => 'MDTA-01',
            'nama' => $lembagaNama,
            'jenjang' => 'MDTA',
            'nsm' => $setting?->nsm ?? null,
            'alamat' => $setting?->alamat ?? null,
            'telepon' => $setting?->telepon ?? null,
            'is_active' => true,
            'urutan' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Add lembaga_id column to core tables
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('lembaga_id')->nullable()->after('id')->constrained('lembagas')->cascadeOnDelete();
        });

        Schema::table('santris', function (Blueprint $table) {
            $table->foreignId('lembaga_id')->nullable()->after('id')->constrained('lembagas')->cascadeOnDelete();
        });

        Schema::table('mapels', function (Blueprint $table) {
            $table->foreignId('lembaga_id')->nullable()->after('id')->constrained('lembagas')->cascadeOnDelete();
        });

        // 3. Assign existing records to default lembaga
        DB::table('kelas')->whereNull('lembaga_id')->update(['lembaga_id' => $defaultLembagaId]);
        DB::table('santris')->whereNull('lembaga_id')->update(['lembaga_id' => $defaultLembagaId]);
        DB::table('mapels')->whereNull('lembaga_id')->update(['lembaga_id' => $defaultLembagaId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->dropColumn('lembaga_id');
        });

        Schema::table('santris', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->dropColumn('lembaga_id');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['lembaga_id']);
            $table->dropColumn('lembaga_id');
        });
    }
};
