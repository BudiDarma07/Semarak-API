<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Role::truncate();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'dinas']);

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
