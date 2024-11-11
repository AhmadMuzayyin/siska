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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('lembaga');
            $table->text('alamat');
            $table->string('email');
            $table->string('telepon');
            $table->string('logo');
            $table->string('favicon');
            $table->string('meta_deskripsi')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('nsm')->nullable();
            $table->boolean('fitur_pesan_whatsapp')->default(false);
            $table->text('pesan_whatsapp')->nullable();
            $table->text('api_key_whatsapp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
