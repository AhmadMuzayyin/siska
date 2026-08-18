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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lembaga_id')->nullable()->constrained('lembagas')->nullOnDelete();
            $table->string('nama_program');
            $table->string('kategori_badge')->nullable();
            $table->text('deskripsi_singkat');
            $table->json('materi_unggulan')->nullable();
            $table->string('gambar_url', 500)->nullable();
            $table->string('icon', 50)->nullable()->default('book-open');
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'urutan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
