<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Non-aktifkan foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data lama
        DB::table('users')->truncate();
        DB::table('roles')->truncate();
        DB::table('materials')->truncate();

        // Aktifkan foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ================== SEED ROLES ================== //
        $roles = [
            ['id' => 1, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'petugas', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'petugas_yanbung', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('roles')->insert($roles);

        // ================== SEED USERS ================== //
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('petugas123'),
                'role_id' => 2,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Petugas Yanbung',
                'email' => 'petugasyanbung@gmail.com',
                'password' => Hash::make('petugasyanbung123'),
                'role_id' => 3,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('users')->insert($users);

        // ================== SEED MATERIALS ================== //
        $this->call(MaterialSeeder::class);

        // ================== INFO LOGIN ================== //
        $this->command->info('=== DEFAULT LOGIN CREDENTIALS ===');
        $this->command->info('Admin: admin@gmail.com / admin123');
        $this->command->info('Petugas: petugas@gmail.com / petugas123');
        $this->command->info('Petugas Yanbung: petugasyanbung@gmail.com / petugasyanbung123');
        $this->command->info('==================================');
    }
}