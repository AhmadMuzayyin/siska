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
        if (! Schema::hasColumn('contacts', 'is_dibaca')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->boolean('is_dibaca')->default(false)->index()->after('message');
            });
        }

        if (! Schema::hasColumn('santris', 'notification_read_at')) {
            Schema::table('santris', function (Blueprint $table) {
                $table->timestamp('notification_read_at')->nullable()->index()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('contacts', 'is_dibaca')) {
            Schema::table('contacts', function (Blueprint $table) {
                $table->dropColumn('is_dibaca');
            });
        }

        if (Schema::hasColumn('santris', 'notification_read_at')) {
            Schema::table('santris', function (Blueprint $table) {
                $table->dropColumn('notification_read_at');
            });
        }
    }
};
