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
        if (! Schema::hasColumn('gurus', 'notification_read_at')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->timestamp('notification_read_at')->nullable()->index()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('gurus', 'notification_read_at')) {
            Schema::table('gurus', function (Blueprint $table) {
                $table->dropColumn('notification_read_at');
            });
        }
    }
};
