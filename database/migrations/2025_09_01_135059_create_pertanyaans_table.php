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
        Schema::create('pertanyaans', function (Blueprint $table) {
            $table->id('id_pertanyaan');
            $table->foreignId('kategori_id')->constrained('kategoris', 'id_kategori')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('tipe_jawaban');
            $table->enum('jenis_pertanyaan', ['pemeliharaan', 'tugas', 'tindak_lanjut']);
            $table->integer('urutan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaans');
    }
};
