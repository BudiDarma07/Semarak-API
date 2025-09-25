<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertanyaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_pertanyaan';

    protected $fillable = ['kategori_id', 'pertanyaan', 'tipe_jawaban', 'jenis_pertanyaan', 'urutan'];

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    public function jawaban() {
        return $this->hasMany(Jawaban::class, 'pertanyaan_id', 'id_pertanyaan');
    }
}
