<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jawaban';
    
    protected $fillable = ['pertanyaan_id', 'laporan_id', 'jawaban'];

    public function pertanyaan() {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'id_pertanyaan');
    }

    public function laporan() {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id_laporan');
    }
}
