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
        Schema::create('nilai_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_nilai_harian_id')->constrained('kategori_nilai_harians')->cascadeOnDelete();
            $table->foreignId('santri_id')->constrained('santris')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('nilai')->default(0); // 0 - 100
            $table->text('catatan')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['kategori_nilai_harian_id', 'santri_id', 'semester_id', 'tanggal'], 'unique_nilai_harian_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_harians');
    }
};
