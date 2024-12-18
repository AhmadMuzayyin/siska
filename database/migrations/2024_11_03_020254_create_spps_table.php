<?php

use App\Models\Santri;
use App\Models\Semester;
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
        Schema::create('spps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Semester::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Santri::class)->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->integer('nominal');
            $table->string('status')->default('Belum Lunas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spps');
    }
};
