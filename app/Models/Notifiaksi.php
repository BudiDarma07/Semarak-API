<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifiaksi extends Model
{
    use HasFactory;

    protected $fillable = ['laporan_id', 'pesan'];

    public function laporan() {
        return $this->belongsTo(Laporan::class, 'laporan_id', 'id_laporan');
    }
}
