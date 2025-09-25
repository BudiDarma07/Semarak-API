<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_dokumentasi';

    protected $fillable = ['laporan_id', 'file_path', 'file_name', 'file_size'];

    public function laporan() {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id_laporan');
    }
}
