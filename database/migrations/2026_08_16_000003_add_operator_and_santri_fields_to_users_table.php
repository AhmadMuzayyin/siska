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
        if (! Schema::hasColumn('users', 'lembaga_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('lembaga_id')->nullable()->after('role')->constrained('lembagas')->nullOnDelete();
            });
        }

        if (! Schema::hasColumn('users', 'santri_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('santri_id')->nullable()->after('lembaga_id')->constrained('santris')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'santri_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['santri_id']);
                $table->dropColumn('santri_id');
            });
        }

        if (Schema::hasColumn('users', 'lembaga_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['lembaga_id']);
                $table->dropColumn('lembaga_id');
            });
        }
    }
};
