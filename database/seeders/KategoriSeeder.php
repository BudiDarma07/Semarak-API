<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Kategori::truncate();

        Kategori::create(['nama_kategori' => 'Appar']);
        Kategori::create(['nama_kategori' => 'AC']);
        Kategori::create(['nama_kategori' => 'Lift']);
        Kategori::create(['nama_kategori' => 'Genset']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
