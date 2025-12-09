<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Print</title>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                padding: 20px;
                color: #000;
                background: #fff !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #000;
            }
            
            .print-header h1 {
                margin: 0 0 5px 0;
                font-size: 18px;
                font-weight: bold;
            }
            
            .print-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
                padding: 8px;
                background-color: #f5f5f5;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 15px;
                font-size: 11px;
            }
            
            table th {
                background-color: #e0e0e0 !important;
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
                font-weight: bold;
            }
            
            table td {
                border: 1px solid #000;
                padding: 6px;
            }
            
            .text-center {
                text-align: center;
            }
            
            .footer {
                margin-top: 20px;
                padding-top: 10px;
                border-top: 1px solid #000;
                text-align: center;
                font-size: 10px;
            }
        }
        
        @media screen {
            body {
                background: #f5f5f5;
                padding: 20px;
            }
            
            .print-container {
                max-width: 1200px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            
            .print-controls {
                margin-bottom: 20px;
                text-align: center;
            }
            
            .btn {
                padding: 10px 20px;
                margin: 0 5px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
            }
            
            .btn-print {
                background: #007bff;
                color: white;
            }
            
            .btn-back {
                background: #6c757d;
                color: white;
                text-decoration: none;
                display: inline-block;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <div class="print-controls no-print">
            <button class="btn btn-print" onclick="window.print()">
                🖨️ Cetak
            </button>
            <a href="{{ route('admin.transaksi.index', ['tab' => $jenis]) }}" class="btn btn-back">
                ↩️ Kembali
            </a>
        </div>
        
        <div class="print-header">
            <h1>{{ $title }}</h1>
            <p>Sistem Inventaris Material</p>
            <p>Tanggal Cetak: {{ date('d F Y H:i:s') }}</p>
        </div>
        
        <div class="print-info">
            <div>
                <strong>Jenis:</strong> {{ ucfirst($jenis) }}
            </div>
            <div>
                <strong>Total Data:</strong> {{ $transaksi->count() }}
            </div>
            <div>
                <strong>Dicetak Oleh:</strong> {{ auth()->user()->name }}
            </div>
        </div>
        
        @if($transaksi->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        @if($jenis == 'pengeluaran')
                        <th>Pengambil</th>
                        <th>ID Pelanggan</th>
                        @else
                        <th>Penerima</th>
                        @endif
                        <th>Keperluan</th>
                        <th>Material</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->kode_transaksi }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        
                        @if($jenis == 'pengeluaran')
                        <td>{{ $item->nama_pihak_transaksi }}</td>
                        <td>{{ $item->nomor_pelanggan }}</td>
                        @else
                        <td>{{ $item->nama_pihak_transaksi }}</td>
                        @endif
                        
                        <td>{{ $item->keperluan }}</td>
                        
                        <td>
                            @foreach($item->details as $detail)
                                {{ $detail->material->nama_material }}<br>
                            @endforeach
                        </td>
                        
                        <td class="text-center">
                            @foreach($item->details as $detail)
                                {{ $detail->jumlah }} {{ $detail->material->satuan }}<br>
                            @endforeach
                        </td>
                        
                        <td>
                            @if($item->status == 'disetujui')
                                ✅ Disetujui
                            @elseif($item->status == 'dikembalikan')
                                ❌ Dikembalikan
                            @else
                                ⏳ Menunggu
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 40px;">
                <h3>Tidak ada data transaksi</h3>
            </div>
        @endif
        
        <div class="footer">
            <p>Halaman 1/1 • Dicetak dari Sistem Inventaris Material</p>
        </div>
    </div>
    
    <script>
    window.addEventListener('load', function() {
        // Auto print jika diperlukan
        if (window.location.search.includes('autoprint=true')) {
            window.print();
        }
    });
    </script>
</body>
</html>