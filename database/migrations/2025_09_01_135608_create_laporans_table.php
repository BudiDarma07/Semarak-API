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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->foreignId('aset_id')->constrained('asets', 'id_aset')->onDelete('cascade');
            $table->string('nama_teknisi');
            $table->enum('tipe_laporan', ['pemeliharaan', 'tindak_lanjut']);
            $table->enum('status', ['belum proses', 'selesai'])->default('belum proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
