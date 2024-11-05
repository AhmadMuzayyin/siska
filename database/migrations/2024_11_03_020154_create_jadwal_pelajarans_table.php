<?php

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TahunAkademik::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Kelas::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Mapel::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Guru::class)->constrained()->cascadeOnDelete();
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajarans');
    }
};
