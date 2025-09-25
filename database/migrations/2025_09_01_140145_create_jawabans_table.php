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
        Schema::create('jawabans', function (Blueprint $table) {
            $table->id('id_jawaban');
            $table->foreignId('pertanyaan_id')->constrained('pertanyaans', 'id_pertanyaan')->onDelete('cascade');
            $table->foreignId('laporan_id')->constrained('laporans', 'id_laporan')->onDelete('cascade');
            $table->text('jawaban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawabans');
    }
};
