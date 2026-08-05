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
        Schema::create('gaji_gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus')->cascadeOnDelete();
            $table->unsignedBigInteger('bisyaroh');
            $table->unsignedSmallInteger('jumlah_hadir');
            $table->unsignedBigInteger('total_gaji');
            $table->unsignedTinyInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->timestamps();

            $table->unique(['guru_id', 'semester_id', 'bulan', 'tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gaji_gurus');
    }
};
