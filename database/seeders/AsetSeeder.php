<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Aset::truncate();

        $kategoriAppar = Kategori::where('nama_kategori', 'Appar')->first();
        $kategoriAC = Kategori::where('nama_kategori', 'AC')->first();
        $kategoriLift = Kategori::where('nama_kategori', 'Lift')->first();
        $kategoriGenset = Kategori::where('nama_kategori', 'Genset')->first();

        if ($kategoriAppar) {
            Aset::create([
                'kode_aset' => 'APR-LO-TL',
                'nama_aset' => 'Appar Toilet Loby',
                'lokasi' => 'Loby',
                'kategori_id' => $kategoriAppar->id_kategori,
            ]);
        }

        if ($kategoriAC) {
            Aset::create([
                'kode_aset' => 'AC-LO-L',
                'nama_aset' => 'AC Loby',
                'lokasi' => 'Loby',
                'kategori_id' => $kategoriAC->id_kategori,
            ]);
        }

        if ($kategoriLift) {
            Aset::create([
                'kode_aset' => 'LFT-PL1',
                'nama_aset' => 'Lift PL1 (GF3-PL1)',
                'lokasi' => 'PL1 (GF3-PL1)',
                'kategori_id' => $kategoriLift->id_kategori,
            ]);
        }

        if ($kategoriGenset) {
            Aset::create([
                'kode_aset' => 'GST-RG1',
                'nama_aset' => 'Genset Rumah Genset 1',
                'lokasi' => 'Rumah Genset 1',
                'kategori_id' => $kategoriGenset->id_kategori,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}