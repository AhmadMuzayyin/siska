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
        Schema::create('kategori_nilai_harians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->nullable()->constrained('lembagas')->cascadeOnDelete();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->unsignedInteger('bobot')->default(10); // Percent weight e.g. 15%
            $table->boolean('is_wajib')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_nilai_harians');
    }
};
