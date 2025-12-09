<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data existing terlebih dahulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->delete();
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1, // admin
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Petugas',
            'email' => 'petugas@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // petugas
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Petugas Yanbung',
            'email' => 'petugasyanbung@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 3, // petugas yanbung
            'email_verified_at' => now(),
        ]);
    }
}