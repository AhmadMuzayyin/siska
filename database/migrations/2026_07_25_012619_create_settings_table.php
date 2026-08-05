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
            $table->boolean('is_installed')->default(true);
            $table->boolean('is_multi_lembaga')->default(true);
            $table->json('installed_modules')->nullable();
            $table->string('app_version')->default('2.0.0');
            $table->string('lembaga')->nullable();
            $table->string('nsm')->nullable();
            $table->string('alamat')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('meta_deskripsi')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->unsignedTinyInteger('payroll_cutoff_day')->default(30);
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
