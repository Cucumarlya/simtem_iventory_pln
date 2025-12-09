<?php

namespace App\Exports;

use App\Models\Material;
use App\Models\TransaksiMaterialDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapStokExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        $materials = Material::orderBy('nama_material')->get();
        
        foreach ($materials as $material) {
            $totalPenerimaan = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                $q->where('jenis', 'penerimaan')->where('status', 'disetujui');
            })->where('material_id', $material->id)->sum('jumlah');
            
            $totalPengeluaran = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                $q->where('jenis', 'pengeluaran')->where('status', 'disetujui');
            })->where('material_id', $material->id)->sum('jumlah');
            
            $material->stok_aktual = $material->stok + $totalPenerimaan - $totalPengeluaran;
            $material->total_penerimaan = $totalPenerimaan;
            $material->total_pengeluaran = $totalPengeluaran;
        }
        
        return $materials;
    }

    public function headings(): array
    {
        return [
            'Kode Material',
            'Nama Material',
            'Satuan',
            'Stok Awal',
            'Total Penerimaan',
            'Total Pengeluaran',
            'Stok Akhir',
            'Harga Satuan',
            'Nilai Stok'
        ];
    }

    public function map($material): array
    {
        return [
            $material->kode_material,
            $material->nama_material,
            $material->satuan,
            $material->stok,
            $material->total_penerimaan,
            $material->total_pengeluaran,
            $material->stok_aktual,
            'Rp ' . number_format($material->harga_satuan, 0, ',', '.'),
            'Rp ' . number_format($material->stok_aktual * $material->harga_satuan, 0, ',', '.')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}