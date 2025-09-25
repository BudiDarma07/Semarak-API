<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_aset';

    protected $fillable = ['kode_aset', 'nama_aset', 'lokasi', 'kategori_id'];

    public function getRouteKeyName()
    {
        return 'kode_aset';
    }

    public function kategori() {
        return $this->belongsTo(Kategori::class, 'kategori_id', 'id_kategori');
    }

    public function laporans() {
        return $this->hasMany(Laporan::class, 'aset_id', 'id_aset');
    }
}
