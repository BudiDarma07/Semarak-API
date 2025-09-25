<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_laporan';

    protected $fillable = ['aset_id', 'nama_teknisi', 'tipe_laporan', 'status',];

    public function aset() {
        return $this->belongsTo(Aset::class, 'aset_id', 'id_aset');
    }

    public function jawabans() {
        return $this->hasMany(Jawaban::class, 'laporan_id', 'id_laporan');
    }

    public function dokumentasis() {
        return $this->hasMany(Dokumentasi::class, 'laporan_id', 'id_laporan');
    }
}
