<?php

namespace App\Exports;

use App\Models\TransaksiMaterial;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $jenis;
    
    public function __construct($jenis)
    {
        $this->jenis = $jenis;
    }
    
    public function collection()
    {
        return TransaksiMaterial::where('jenis', $this->jenis)
            ->with(['details.material', 'user', 'verifikator'])
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function headings(): array
    {
        if ($this->jenis == 'penerimaan') {
            return [
                'NO',
                'KODE TRANSAKSI',
                'TANGGAL',
                'NAMA PENERIMA',
                'KEPERLUAN',
                'MATERIAL',
                'JUMLAH TOTAL',
                'STATUS',
                'DIBUAT OLEH',
                'DIVERIFIKASI OLEH',
                'TANGGAL VERIFIKASI'
            ];
        } else {
            return [
                'NO',
                'KODE TRANSAKSI',
                'TANGGAL',
                'NAMA PENGAMBIL',
                'KEPERLUAN',
                'ID PELANGGAN',
                'MATERIAL',
                'JUMLAH TOTAL',
                'STATUS',
                'DIBUAT OLEH',
                'DIVERIFIKASI OLEH',
                'TANGGAL VERIFIKASI'
            ];
        }
    }
    
    public function map($transaksi): array
    {
        static $counter = 0;
        $counter++;
        
        $materialList = $transaksi->details->map(function($detail) {
            return $detail->material->nama_material . ' (' . $detail->jumlah . ' ' . $detail->material->satuan . ')';
        })->implode(', ');
        
        $jumlahTotal = $transaksi->details->sum('jumlah');
        
        $status = match($transaksi->status) {
            'disetujui' => 'DISETUJUI',
            'dikembalikan' => 'DIKEMBALIKAN',
            default => 'MENUNGGU'
        };
        
        if ($this->jenis == 'penerimaan') {
            return [
                $counter,
                $transaksi->kode_transaksi,
                $transaksi->tanggal->format('d/m/Y'),
                $transaksi->nama_pihak_transaksi,
                $transaksi->keperluan,
                $materialList,
                $jumlahTotal,
                $status,
                $transaksi->user->name ?? '-',
                $transaksi->verifikator->name ?? '-',
                $transaksi->tanggal_verifikasi ? $transaksi->tanggal_verifikasi->format('d/m/Y H:i') : '-'
            ];
        } else {
            return [
                $counter,
                $transaksi->kode_transaksi,
                $transaksi->tanggal->format('d/m/Y'),
                $transaksi->nama_pihak_transaksi,
                $transaksi->keperluan,
                $transaksi->nomor_pelanggan,
                $materialList,
                $jumlahTotal,
                $status,
                $transaksi->user->name ?? '-',
                $transaksi->verifikator->name ?? '-',
                $transaksi->tanggal_verifikasi ? $transaksi->tanggal_verifikasi->format('d/m/Y H:i') : '-'
            ];
        }
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'A:K' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }
}