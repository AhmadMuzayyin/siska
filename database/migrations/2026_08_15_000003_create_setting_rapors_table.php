<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_rapors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->nullable()->constrained('lembagas')->cascadeOnDelete();
            $table->foreignId('mapel_id')->nullable()->constrained('mapels')->cascadeOnDelete();
            $table->text('deskripsi_a')->nullable();
            $table->text('deskripsi_b')->nullable();
            $table->text('deskripsi_c')->nullable();
            $table->text('deskripsi_d')->nullable();
            $table->string('template_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_rapors');
    }
};
