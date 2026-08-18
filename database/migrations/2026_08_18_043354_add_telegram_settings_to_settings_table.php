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
        Schema::table('settings', function (Blueprint $table) {
            if (! Schema::hasColumn('settings', 'telegram_bot_token')) {
                $table->string('telegram_bot_token')->nullable()->after('is_input_nilai_open');
            }
            if (! Schema::hasColumn('settings', 'telegram_admin_chat_id')) {
                $table->string('telegram_admin_chat_id')->nullable()->after('telegram_bot_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'telegram_admin_chat_id')) {
                $table->dropColumn('telegram_admin_chat_id');
            }
            if (Schema::hasColumn('settings', 'telegram_bot_token')) {
                $table->dropColumn('telegram_bot_token');
            }
        });
    }
};
