<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Kosongkan tabel roles
        DB::table('roles')->truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $roles = [
            [
                'name' => 'admin',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'petugas', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'petugas_yanbung',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}