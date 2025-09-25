<?php

namespace App\Exports;

use App\Models\Laporan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporaExport implements FromQuery, WithHeadings, WithMapping
{
    protected $month;
    protected $year;
    protected $tipe_laporan;
    
    public function __construct($month, $year, $tipe_laporan) 
    {
        $this->month = $month;
        $this->year = $year;
        $this->tipe_laporan = $tipe_laporan; 
    }

    public function query()
    {
        $query = Laporan::query()->with(['aset', 'aset.kategori']);

        if ($this->month) {
            $query->whereMonth('created_at', $this->month);
        }
        if ($this->year) {
            $query->whereYear('created_at', $this->year);
        }
        if ($this->tipe_laporan) {
            $query->where('tipe_laporan', $this->tipe_laporan);
        }

        return $query->latest();
    }

    public function headings(): array
    {
        return [
            'ID Laporan',
            'Nama Aset',
            'Lokasi',
            'Kategori',
            'Nama Teknisi',
            'Tipe Laporan',
            'Status',
            'Tanggal Laporan',
        ];
    }

    /**
    * (PERUBAHAN 5) Method ini mengatur data dari setiap laporan ke kolom yang benar.
    * @param Laporan $laporan
    */
    public function map($laporan): array
    {
        return [
            $laporan->id_laporan,
            $laporan->aset->nama_aset,
            $laporan->aset->lokasi,
            $laporan->aset->kategori->nama_kategori ?? 'N/A',
            $laporan->nama_teknisi,
            ucfirst(str_replace('_', ' ', $laporan->tipe_laporan)),
            $laporan->status,
            $laporan->created_at->format('d F Y H:i'),
        ];
    }
}
