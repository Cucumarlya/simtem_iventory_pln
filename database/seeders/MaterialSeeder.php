<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;

class MaterialSeeder extends Seeder
{
    public function run()
    {
        $materials = [
            [
                'kode_material' => 'MAT-001',
                'nama_material' => 'SR',
                'satuan' => 'ROLL',
                'stok_awal' => 50,
                'min_stok' => 10,
            ],
            [
                'kode_material' => 'MAT-002',
                'nama_material' => 'MCB 2A',
                'satuan' => 'PCS',
                'stok_awal' => 100,
                'min_stok' => 20,
            ],
            [
                'kode_material' => 'MAT-003',
                'nama_material' => 'Paska',
                'satuan' => 'PCS',
                'stok_awal' => 80,
                'min_stok' => 15,
            ],
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}