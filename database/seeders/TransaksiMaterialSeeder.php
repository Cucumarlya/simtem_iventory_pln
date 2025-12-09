<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiMaterial;
use App\Models\DetailTransaksiMaterial;
use App\Models\Material;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiMaterialSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Kosongkan tabel terlebih dahulu
        DetailTransaksiMaterial::truncate();
        TransaksiMaterial::truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Ambil user admin pertama
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::first();
        }
        
        // Ambil beberapa material untuk contoh
        $materials = Material::take(5)->get();
        if ($materials->isEmpty()) {
            $this->command->error('Tidak ada material yang ditemukan. Silakan seed material terlebih dahulu.');
            return;
        }
        
        $this->command->info('Membuat contoh data transaksi...');
        
        // ========== DATA PENERIMAAN ========== //
        
        // Data 1: Penerimaan - Status Disetujui
        $penerimaan1 = TransaksiMaterial::create([
            'kode_transaksi' => 'TRM-251207-001',
            'tanggal' => Carbon::create(2025, 12, 7),
            'jenis' => 'penerimaan',
            'nama_pihak_transaksi' => 'PT Sumber Jaya',
            'keperluan' => 'YANBUNG',
            'nomor_pelanggan' => null,
            'foto_bukti' => 'transaksi/penerimaan/2025/12/contoh_bukti_1.jpg',
            'foto_sr_sebelum' => null,
            'foto_sr_sesudah' => null,
            'dibuat_oleh' => $admin->id,
            'status' => 'disetujui',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => Carbon::create(2025, 12, 7, 10, 30, 0),
            'verifikator_id' => $admin->id,
            'created_at' => Carbon::create(2025, 12, 7, 9, 0, 0),
            'updated_at' => Carbon::create(2025, 12, 7, 10, 30, 0),
        ]);
        
        // Detail material untuk penerimaan 1
        DetailTransaksiMaterial::create([
            'transaksi_id' => $penerimaan1->id,
            'material_id' => $materials[0]->id,
            'jumlah' => 50,
        ]);
        
        DetailTransaksiMaterial::create([
            'transaksi_id' => $penerimaan1->id,
            'material_id' => $materials[1]->id,
            'jumlah' => 30,
        ]);
        
        // Data 2: Penerimaan - Status Menunggu
        $penerimaan2 = TransaksiMaterial::create([
            'kode_transaksi' => 'TRM-251207-002',
            'tanggal' => Carbon::create(2025, 12, 7),
            'jenis' => 'penerimaan',
            'nama_pihak_transaksi' => 'CV Mandiri Teknik',
            'keperluan' => 'P2TL',
            'nomor_pelanggan' => null,
            'foto_bukti' => 'transaksi/penerimaan/2025/12/contoh_bukti_2.jpg',
            'foto_sr_sebelum' => null,
            'foto_sr_sesudah' => null,
            'dibuat_oleh' => $admin->id,
            'status' => 'menunggu',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => null,
            'verifikator_id' => null,
            'created_at' => Carbon::create(2025, 12, 7, 11, 0, 0),
            'updated_at' => Carbon::create(2025, 12, 7, 11, 0, 0),
        ]);
        
        // Detail material untuk penerimaan 2
        DetailTransaksiMaterial::create([
            'transaksi_id' => $penerimaan2->id,
            'material_id' => $materials[2]->id,
            'jumlah' => 20,
        ]);
        
        DetailTransaksiMaterial::create([
            'transaksi_id' => $penerimaan2->id,
            'material_id' => $materials[3]->id,
            'jumlah' => 15,
        ]);
        
        // ========== DATA PENGELUARAN ========== //
        
        // Data 3: Pengeluaran - Status Disetujui
        $pengeluaran1 = TransaksiMaterial::create([
            'kode_transaksi' => 'TRK-251207-001',
            'tanggal' => Carbon::create(2025, 12, 7),
            'jenis' => 'pengeluaran',
            'nama_pihak_transaksi' => 'Budi Santoso',
            'keperluan' => 'GANGGUAN',
            'nomor_pelanggan' => '1234567890',
            'foto_bukti' => 'transaksi/pengeluaran/2025/12/bukti/contoh_bukti_3.jpg',
            'foto_sr_sebelum' => 'transaksi/pengeluaran/2025/12/sr_sebelum/contoh_sr_sebelum_1.jpg',
            'foto_sr_sesudah' => 'transaksi/pengeluaran/2025/12/sr_sesudah/contoh_sr_sesudah_1.jpg',
            'dibuat_oleh' => $admin->id,
            'status' => 'disetujui',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => Carbon::create(2025, 12, 7, 14, 15, 0),
            'verifikator_id' => $admin->id,
            'created_at' => Carbon::create(2025, 12, 7, 13, 0, 0),
            'updated_at' => Carbon::create(2025, 12, 7, 14, 15, 0),
        ]);
        
        // Detail material untuk pengeluaran 1
        DetailTransaksiMaterial::create([
            'transaksi_id' => $pengeluaran1->id,
            'material_id' => $materials[0]->id,
            'jumlah' => 10,
        ]);
        
        DetailTransaksiMaterial::create([
            'transaksi_id' => $pengeluaran1->id,
            'material_id' => $materials[1]->id,
            'jumlah' => 5,
        ]);
        
        // Data 4: Pengeluaran - Status Menunggu
        $pengeluaran2 = TransaksiMaterial::create([
            'kode_transaksi' => 'TRK-251207-002',
            'tanggal' => Carbon::create(2025, 12, 7),
            'jenis' => 'pengeluaran',
            'nama_pihak_transaksi' => 'Ahmad Rizki',
            'keperluan' => 'PLN',
            'nomor_pelanggan' => '0987654321',
            'foto_bukti' => 'transaksi/pengeluaran/2025/12/bukti/contoh_bukti_4.jpg',
            'foto_sr_sebelum' => 'transaksi/pengeluaran/2025/12/sr_sebelum/contoh_sr_sebelum_2.jpg',
            'foto_sr_sesudah' => 'transaksi/pengeluaran/2025/12/sr_sesudah/contoh_sr_sesudah_2.jpg',
            'dibuat_oleh' => $admin->id,
            'status' => 'menunggu',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => null,
            'verifikator_id' => null,
            'created_at' => Carbon::create(2025, 12, 7, 15, 30, 0),
            'updated_at' => Carbon::create(2025, 12, 7, 15, 30, 0),
        ]);
        
        // Detail material untuk pengeluaran 2
        DetailTransaksiMaterial::create([
            'transaksi_id' => $pengeluaran2->id,
            'material_id' => $materials[2]->id,
            'jumlah' => 8,
        ]);
        
        DetailTransaksiMaterial::create([
            'transaksi_id' => $pengeluaran2->id,
            'material_id' => $materials[3]->id,
            'jumlah' => 12,
        ]);
        
        $this->command->info('Berhasil membuat 4 contoh transaksi:');
        $this->command->info('- 2 Penerimaan (1 Disetujui, 1 Menunggu)');
        $this->command->info('- 2 Pengeluaran (1 Disetujui, 1 Menunggu)');
    }
}