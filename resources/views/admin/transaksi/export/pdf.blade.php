<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Laporan Transaksi' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 20px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        
        .header .subtitle {
            margin: 5px 0;
            color: #666;
            font-size: 13px;
        }
        
        .info-box {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .info-item {
            flex: 1;
        }
        
        .info-item strong {
            display: block;
            margin-bottom: 3px;
            color: #333;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 11px;
        }
        
        table th {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        
        table td {
            border: 1px solid #dee2e6;
            padding: 6px 5px;
            color: #333;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .badge-yanbung {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-p2tl {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-gangguan {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .badge-pln {
            background-color: #fef9c3;
            color: #92400e;
        }
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
        }
        
        .status-disetujui {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-dikembalikan {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-menunggu {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .summary {
            margin-top: 20px;
            padding: 12px;
            background-color: #f8f9fa;
            border-radius: 5px;
            font-size: 12px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #333;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Untuk cetakan */
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                font-size: 10px;
            }
            
            .header {
                margin-bottom: 15px;
                padding-bottom: 10px;
            }
            
            table {
                font-size: 9px;
            }
            
            table th, table td {
                padding: 4px 3px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title ?? 'Laporan Transaksi' }}</h1>
        <div class="subtitle">Sistem Inventaris Material - {{ config('app.name', 'SINVOSAR') }}</div>
        <div class="subtitle">Tanggal Cetak: {{ $tanggal ?? now()->format('d F Y H:i:s') }}</div>
        <div class="subtitle">Jumlah Data: {{ $transaksi->count() ?? 0 }} transaksi</div>
    </div>
    
    @if(isset($jenis))
    <div class="info-box">
        <div class="info-item">
            <strong>Jenis Transaksi</strong>
            <span>{{ $jenis == 'penerimaan' ? 'PENERIMAAN MATERIAL' : 'PENGELUARAN MATERIAL' }}</span>
        </div>
        <div class="info-item">
            <strong>Periode Data</strong>
            <span>
                @if(isset($transaksi) && $transaksi->count() > 0)
                    {{ $transaksi->first()->tanggal->format('d F Y') }} s/d {{ $transaksi->last()->tanggal->format('d F Y') }}
                @else
                    Tidak ada data
                @endif
            </span>
        </div>
        <div class="info-item">
            <strong>Status Data</strong>
            <span>
                Disetujui: {{ isset($transaksi) ? $transaksi->where('status', 'disetujui')->count() : 0 }}, 
                Dikembalikan: {{ isset($transaksi) ? $transaksi->where('status', 'dikembalikan')->count() : 0 }}, 
                Menunggu: {{ isset($transaksi) ? $transaksi->where('status', 'menunggu')->count() : 0 }}
            </span>
        </div>
    </div>
    @endif
    
    @if(isset($transaksi) && $transaksi->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="30" class="text-center">No</th>
                    <th width="120">Kode Transaksi</th>
                    <th width="80">Tanggal</th>
                    @if(isset($jenis) && $jenis == 'pengeluaran')
                    <th>Nama Pengambil</th>
                    <th width="100">ID Pelanggan</th>
                    @else
                    <th>Nama Penerima</th>
                    @endif
                    <th width="80">Keperluan</th>
                    <th width="80">Material</th>
                    <th width="60">Jumlah</th>
                    <th width="80">Status</th>
                    <th width="100">Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $item->kode_transaksi ?? 'N/A' }}</strong></td>
                    <td>{{ $item->tanggal ? $item->tanggal->format('d/m/Y') : 'N/A' }}</td>
                    
                    @if(isset($jenis) && $jenis == 'pengeluaran')
                    <td>{{ $item->nama_pihak_transaksi ?? '-' }}</td>
                    <td>{{ $item->nomor_pelanggan ?? '-' }}</td>
                    @else
                    <td>{{ $item->nama_pihak_transaksi ?? '-' }}</td>
                    @endif
                    
                    <td class="text-center">
                        @if($item->keperluan)
                        <span class="badge badge-{{ strtolower($item->keperluan) }}">
                            {{ $item->keperluan }}
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    
                    <td>
                        @if($item->details && $item->details->count() > 0)
                            @foreach($item->details as $detail)
                                {{ $detail->material->nama_material ?? 'Material tidak ditemukan' }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    
                    <td class="text-center">
                        @if($item->details && $item->details->count() > 0)
                            @foreach($item->details as $detail)
                                {{ $detail->jumlah ?? 0 }} {{ $detail->material->satuan ?? '' }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    
                    <td class="text-center">
                        @if($item->status)
                        <span class="status-badge status-{{ $item->status }}">
                            @if($item->status == 'disetujui')
                                DISETUJUI
                            @elseif($item->status == 'dikembalikan')
                                DIKEMBALIKAN
                            @else
                                MENUNGGU
                            @endif
                        </span>
                        @else
                        -
                        @endif
                    </td>
                    
                    <td>{{ $item->user->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="{{ (isset($jenis) && $jenis == 'pengeluaran') ? 7 : 6 }}" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-center">
                        <strong>
                            @if(isset($transaksi))
                                {{ $transaksi->sum(function($item) { 
                                    return $item->details ? $item->details->sum('jumlah') : 0; 
                                }) }}
                            @else
                                0
                            @endif
                        </strong>
                    </td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="summary">
            <h3>📊 RINGKASAN STATISTIK</h3>
            <div class="summary-row">
                <span>Total Transaksi:</span>
                <strong>{{ $transaksi->count() }}</strong>
            </div>
            <div class="summary-row">
                <span>Total Item Material:</span>
                <strong>
                    @if(isset($transaksi))
                        {{ $transaksi->sum(function($item) { 
                            return $item->details ? $item->details->count() : 0; 
                        }) }}
                    @else
                        0
                    @endif
                </strong>
            </div>
            <div class="summary-row">
                <span>Total Kuantitas:</span>
                <strong>
                    @if(isset($transaksi))
                        {{ $transaksi->sum(function($item) { 
                            return $item->details ? $item->details->sum('jumlah') : 0; 
                        }) }}
                    @else
                        0
                    @endif
                </strong>
            </div>
            <div class="summary-row">
                <span>Disetujui:</span>
                <span class="status-badge status-disetujui">{{ $transaksi->where('status', 'disetujui')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Dikembalikan:</span>
                <span class="status-badge status-dikembalikan">{{ $transaksi->where('status', 'dikembalikan')->count() }}</span>
            </div>
            <div class="summary-row">
                <span>Menunggu:</span>
                <span class="status-badge status-menunggu">{{ $transaksi->where('status', 'menunggu')->count() }}</span>
            </div>
        </div>
    @else
        <div style="text-align: center; padding: 40px; color: #666;">
            <h3>📭 TIDAK ADA DATA</h3>
            <p>Belum ada data transaksi {{ isset($jenis) && $jenis == 'penerimaan' ? 'penerimaan' : 'pengeluaran' }} yang tersedia.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Inventaris Material</p>
        <p>© {{ date('Y') }} - {{ config('app.name', 'SINVOSAR') }} | Halaman 1/1</p>
    </div>
</body>
</html>