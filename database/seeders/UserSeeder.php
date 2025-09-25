<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();

        $roleAdmin = Role::where('name', 'admin')->first();
        $roleDinas = Role::where('name', 'dinas')->first();
        $roleTeknisi = Role::where('name', 'teknisi')->first(); // Pastikan ini ada

        if ($roleAdmin) {
            User::create([
                'name' => 'Budi Santoso',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin123'),
                'role_id' => $roleAdmin->id_role,
            ]);
        }

        if ($roleDinas) {
            User::create([
                'name' => 'Siti Aminah',
                'email' => 'dinas@mail.com',
                'password' => Hash::make('dinas123'),
                'role_id' => $roleDinas->id_role,
            ]);
        }
        
        // Pastikan blok kode untuk teknisi ini ada
        if ($roleTeknisi) {
            User::create([
                'name' => 'Teknisi Handal',
                'email' => 'teknisi@mail.com',
                'password' => Hash::make('teknisi123'),
                'role_id' => $roleTeknisi->id_role,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}