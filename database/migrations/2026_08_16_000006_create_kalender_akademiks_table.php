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
        Schema::create('kalender_akademiks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->foreignId('lembaga_id')->nullable()->constrained('lembagas')->cascadeOnDelete();
            $table->string('judul');
            $table->string('tipe')->default('kegiatan');
            $table->date('mulai');
            $table->date('selesai')->nullable();
            $table->string('warna')->default('#10b981');
            $table->string('ikon')->default('calendar');
            $table->text('deskripsi')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalender_akademiks');
    }
};
