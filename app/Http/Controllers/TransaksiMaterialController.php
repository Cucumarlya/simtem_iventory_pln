<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;
use App\Models\DetailTransaksiMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransaksiMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'penerimaan');
        
        // Data dummy untuk demo
        $dummyPenerimaanAll = [
            [
                'id' => 1,
                'kode_transaksi' => 'TRM-240115-001',
                'tanggal' => '2024-01-15',
                'nama_pihak_transaksi' => 'PT. Supplier A',
                'keperluan' => 'YANBUNG',
                'status' => 'disetujui',
                'input_oleh' => 'admin',
                'nomor_pelanggan' => null,
                'jenis' => 'penerimaan'
            ],
            [
                'id' => 3,
                'kode_transaksi' => 'TRM-240117-002',
                'tanggal' => '2024-01-17',
                'nama_pihak_transaksi' => 'UD. Jaya Abadi',
                'keperluan' => 'GANGGUAN',
                'status' => 'dikembalikan',
                'input_oleh' => 'petugas',
                'nomor_pelanggan' => null,
                'jenis' => 'penerimaan'
            ],
            [
                'id' => 4,
                'kode_transaksi' => 'TRM-240118-003',
                'tanggal' => '2024-01-18',
                'nama_pihak_transaksi' => 'PT. Bangun Cipta',
                'keperluan' => 'P2TL',
                'status' => 'disetujui',
                'input_oleh' => 'petugas',
                'nomor_pelanggan' => null,
                'jenis' => 'penerimaan'
            ],
            [
                'id' => 5,
                'kode_transaksi' => 'TRM-240119-004',
                'tanggal' => '2024-01-19',
                'nama_pihak_transaksi' => 'PT. Material Utama',
                'keperluan' => 'YANBUNG',
                'status' => 'disetujui',
                'input_oleh' => 'admin',
                'nomor_pelanggan' => null,
                'jenis' => 'penerimaan'
            ]
        ];
        
        $dummyPengeluaranAll = [
            [
                'id' => 6,
                'kode_transaksi' => 'TRK-240115-001',
                'tanggal' => '2024-01-15',
                'nama_pihak_transaksi' => 'Budi Santoso',
                'keperluan' => 'YANBUNG',
                'status' => 'disetujui',
                'nomor_pelanggan' => '532110123456',
                'input_oleh' => 'admin',
                'jenis' => 'pengeluaran'
            ],
            [
                'id' => 8,
                'kode_transaksi' => 'TRK-240117-002',
                'tanggal' => '2024-01-17',
                'nama_pihak_transaksi' => 'Ahmad Fauzi',
                'keperluan' => 'GANGGUAN',
                'status' => 'dikembalikan',
                'nomor_pelanggan' => '532110345678',
                'input_oleh' => 'petugas',
                'jenis' => 'pengeluaran'
            ],
            [
                'id' => 9,
                'kode_transaksi' => 'TRK-240118-003',
                'tanggal' => '2024-01-18',
                'nama_pihak_transaksi' => 'Rina Wulandari',
                'keperluan' => 'PLN',
                'status' => 'disetujui',
                'nomor_pelanggan' => '532110901234',
                'input_oleh' => 'admin',
                'jenis' => 'pengeluaran'
            ],
            [
                'id' => 10,
                'kode_transaksi' => 'TRK-240119-004',
                'tanggal' => '2024-01-19',
                'nama_pihak_transaksi' => 'Dewi Susanti',
                'keperluan' => 'P2TL',
                'status' => 'disetujui',
                'nomor_pelanggan' => '532110567890',
                'input_oleh' => 'petugas',
                'jenis' => 'pengeluaran'
            ]
        ];
        
        // Filter berdasarkan jenis
        if ($tab === 'penerimaan') {
            $penerimaanData = array_filter($dummyPenerimaanAll, function($item) {
                return in_array($item['status'], ['disetujui', 'dikembalikan']);
            });
            $penerimaanData = array_values($penerimaanData);
            $pengeluaranData = [];
        } else {
            $pengeluaranData = array_filter($dummyPengeluaranAll, function($item) {
                return in_array($item['status'], ['disetujui', 'dikembalikan']);
            });
            $pengeluaranData = array_values($pengeluaranData);
            $penerimaanData = [];
        }
        
        return view('admin.transaksi.index', compact('tab', 'penerimaanData', 'pengeluaranData'));
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create($jenis = null)
    {
        if (!$jenis) {
            return redirect()->route('admin.transaksi.index');
        }
        
        if (!in_array($jenis, ['penerimaan', 'pengeluaran'])) {
            abort(404, 'Jenis transaksi tidak valid');
        }
        
        $materials = Material::orderBy('nama_material')->get();
        
        if ($jenis == 'penerimaan') {
            return view('admin.transaksi.create-penerimaan', compact('jenis', 'materials'));
        } else {
            return view('admin.transaksi.create-pengeluaran', compact('jenis', 'materials'));
        }
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Validasi berdasarkan jenis
            $validatedData = [];
            
            if ($request->jenis == 'penerimaan') {
                $validatedData = $request->validate([
                    'jenis' => 'required|in:penerimaan,pengeluaran',
                    'tanggal' => 'required|date',
                    'nama_pihak_transaksi' => 'required|string|max:100',
                    'keperluan' => 'required|in:YANBUNG,P2TL,GANGGUAN,PLN',
                    'material' => 'required|array|min:1',
                    'material.*.id' => 'required|exists:materials,id',
                    'material.*.jumlah' => 'required|integer|min:1',
                    'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                ]);
            } else {
                $validatedData = $request->validate([
                    'jenis' => 'required|in:penerimaan,pengeluaran',
                    'tanggal' => 'required|date',
                    'nama_pihak_transaksi' => 'required|string|max:100',
                    'keperluan' => 'required|in:YANBUNG,P2TL,GANGGUAN,PLN',
                    'nomor_pelanggan' => 'required|string|max:50',
                    'material' => 'required|array|min:1',
                    'material.*.id' => 'required|exists:materials,id',
                    'material.*.jumlah' => 'required|integer|min:1',
                    'foto_sr_sebelum' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                    'foto_sr_sesudah' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
                    'foto_bukti' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                ]);
                
                // Cek stok untuk pengeluaran
                foreach ($request->material as $item) {
                    $material = Material::find($item['id']);
                    if (!$material) {
                        return back()->withErrors([
                            'material' => "Material tidak ditemukan"
                        ])->withInput();
                    }
                    
                    $stokTersedia = $material->stok ?? 0;
                    
                    if ($stokTersedia < $item['jumlah']) {
                        return back()->withErrors([
                            'material' => "Stok {$material->nama_material} tidak cukup. Stok tersedia: {$stokTersedia}"
                        ])->withInput();
                    }
                }
            }
            
            // Generate kode transaksi
            $kode = $this->generateKodeTransaksi($request->jenis);
            
            // Handle file uploads
            $fotoPaths = [];
            
            if ($request->jenis == 'penerimaan') {
                if ($request->hasFile('foto_bukti')) {
                    $fotoPaths['bukti'] = $request->file('foto_bukti')->store(
                        'transaksi/penerimaan/' . date('Y/m'),
                        'public'
                    );
                } else {
                    throw new \Exception('Foto bukti penerimaan wajib diupload');
                }
            } else {
                if ($request->hasFile('foto_sr_sebelum')) {
                    $fotoPaths['sr_sebelum'] = $request->file('foto_sr_sebelum')->store(
                        'transaksi/pengeluaran/' . date('Y/m') . '/sr_sebelum',
                        'public'
                    );
                }
                
                if ($request->hasFile('foto_sr_sesudah')) {
                    $fotoPaths['sr_sesudah'] = $request->file('foto_sr_sesudah')->store(
                        'transaksi/pengeluaran/' . date('Y/m') . '/sr_sesudah',
                        'public'
                    );
                }
                
                if ($request->hasFile('foto_bukti')) {
                    $fotoPaths['bukti'] = $request->file('foto_bukti')->store(
                        'transaksi/pengeluaran/' . date('Y/m') . '/bukti',
                        'public'
                    );
                } else {
                    throw new \Exception('Foto bukti pengeluaran wajib diupload');
                }
            }
            
            // Create transaksi
            $transaksi = TransaksiMaterial::create([
                'kode_transaksi' => $kode,
                'jenis' => $request->jenis,
                'tanggal' => $request->tanggal,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan' => $request->keperluan,
                'nomor_pelanggan' => $request->nomor_pelanggan ?? null,
                'foto_sr_sebelum' => $fotoPaths['sr_sebelum'] ?? null,
                'foto_sr_sesudah' => $fotoPaths['sr_sesudah'] ?? null,
                'foto_bukti' => $fotoPaths['bukti'],
                'dibuat_oleh' => Auth::id(),
                'status' => 'menunggu',
                'alasan_penolakan' => null,
                'tanggal_verifikasi' => null,
                'verifikator_id' => null,
            ]);
            
            // Create transaksi details
            foreach ($request->material as $item) {
                DetailTransaksiMaterial::create([
                    'transaksi_id' => $transaksi->id,
                    'material_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);
                
                // Update stok untuk pengeluaran
                if ($request->jenis == 'pengeluaran') {
                    $material = Material::find($item['id']);
                    if ($material) {
                        $material->decrement('stok', $item['jumlah']);
                    }
                }
            }
            
            // Update stok untuk penerimaan
            if ($request->jenis == 'penerimaan') {
                foreach ($request->material as $item) {
                    $material = Material::find($item['id']);
                    if ($material) {
                        $material->increment('stok', $item['jumlah']);
                    }
                }
            }
            
            DB::commit();
            
            $message = 'Transaksi ' . ($request->jenis == 'penerimaan' ? 'Penerimaan' : 'Pengeluaran') . ' berhasil disimpan dan menunggu verifikasi';
            
            return redirect()->route('admin.transaksi.show', $transaksi->id)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded files if error occurs
            if (isset($fotoPaths)) {
                foreach ($fotoPaths as $path) {
                    if ($path && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Data dummy untuk demo
        $transaksiData = [
            'id' => $id,
            'kode_transaksi' => $id == 6 ? 'TRK-240115-001' : 'TRM-240115-001',
            'jenis' => $id == 6 ? 'pengeluaran' : 'penerimaan',
            'tanggal' => Carbon::parse('2024-01-15'),
            'nama_pihak_transaksi' => $id == 6 ? 'Budi Santoso' : 'PT. Supplier A',
            'keperluan' => 'YANBUNG',
            'nomor_pelanggan' => $id == 6 ? '532110123456' : null,
            'foto_sr_sebelum' => $id == 6 ? 'transaksi/pengeluaran/2024/01/sr_sebelum/example.jpg' : null,
            'foto_sr_sesudah' => $id == 6 ? 'transaksi/pengeluaran/2024/01/sr_sesudah/example.jpg' : null,
            'foto_bukti' => 'transaksi/penerimaan/2024/01/example.jpg',
            'status' => 'menunggu',
            'alasan_penolakan' => null,
            'tanggal_verifikasi' => null,
            'created_at' => Carbon::now(),
            'details' => collect([
                (object)[
                    'material_id' => 1,
                    'jumlah' => 10,
                    'material' => (object)[
                        'id' => 1,
                        'kode_material' => 'KBL-001',
                        'nama_material' => 'Kabel NYY 4x25 mm²',
                        'satuan' => 'roll'
                    ]
                ]
            ]),
            'user' => (object)['name' => 'Admin'],
            'verifikator' => null
        ];
        
        $transaksi = (object)$transaksiData;
        
        return view('admin.transaksi.show', compact('transaksi'));
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Data dummy untuk demo
        $transaksiData = [
            'id' => $id,
            'kode_transaksi' => 'TRM-240115-001',
            'jenis' => 'penerimaan',
            'tanggal' => Carbon::parse('2024-01-15'),
            'nama_pihak_transaksi' => 'PT. Supplier A',
            'keperluan' => 'YANBUNG',
            'nomor_pelanggan' => null,
            'foto_sr_sebelum' => null,
            'foto_sr_sesudah' => null,
            'foto_bukti' => 'transaksi/penerimaan/2024/01/example.jpg',
            'status' => 'menunggu',
            'details' => collect([
                (object)[
                    'material_id' => 1,
                    'jumlah' => 10,
                    'material' => (object)[
                        'id' => 1,
                        'kode_material' => 'KBL-001',
                        'nama_material' => 'Kabel NYY 4x25 mm²',
                        'satuan' => 'roll'
                    ]
                ]
            ])
        ];
        
        $transaksi = (object)$transaksiData;
        $materials = Material::all();
        
        return view('admin.transaksi.edit', compact('transaksi', 'materials'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            // Validasi
            $validatedData = $request->validate([
                'tanggal' => 'required|date',
                'nama_pihak_transaksi' => 'required|string|max:100',
                'keperluan' => 'required|in:YANBUNG,P2TL,GANGGUAN,PLN',
                'material' => 'required|array|min:1',
                'material.*.id' => 'required|exists:materials,id',
                'material.*.jumlah' => 'required|integer|min:1',
            ]);
            
            if ($request->jenis == 'pengeluaran') {
                $request->validate([
                    'nomor_pelanggan' => 'required|string|max:50',
                ]);
            }
            
            // Update transaksi
            $transaksi = TransaksiMaterial::findOrFail($id);
            
            // Update data dasar
            $transaksi->update([
                'tanggal' => $request->tanggal,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan' => $request->keperluan,
                'nomor_pelanggan' => $request->nomor_pelanggan ?? $transaksi->nomor_pelanggan,
            ]);
            
            // Handle file uploads jika ada yang diupload
            if ($request->hasFile('foto_bukti')) {
                // Hapus foto lama jika ada
                if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti)) {
                    Storage::disk('public')->delete($transaksi->foto_bukti);
                }
                
                // Simpan foto baru
                $path = $request->file('foto_bukti')->store(
                    'transaksi/' . $transaksi->jenis . '/' . date('Y/m'),
                    'public'
                );
                $transaksi->update(['foto_bukti' => $path]);
            }
            
            if ($transaksi->jenis == 'pengeluaran') {
                if ($request->hasFile('foto_sr_sebelum')) {
                    // Hapus foto lama jika ada
                    if ($transaksi->foto_sr_sebelum && Storage::disk('public')->exists($transaksi->foto_sr_sebelum)) {
                        Storage::disk('public')->delete($transaksi->foto_sr_sebelum);
                    }
                    
                    // Simpan foto baru
                    $path = $request->file('foto_sr_sebelum')->store(
                        'transaksi/pengeluaran/' . date('Y/m') . '/sr_sebelum',
                        'public'
                    );
                    $transaksi->update(['foto_sr_sebelum' => $path]);
                }
                
                if ($request->hasFile('foto_sr_sesudah')) {
                    // Hapus foto lama jika ada
                    if ($transaksi->foto_sr_sesudah && Storage::disk('public')->exists($transaksi->foto_sr_sesudah)) {
                        Storage::disk('public')->delete($transaksi->foto_sr_sesudah);
                    }
                    
                    // Simpan foto baru
                    $path = $request->file('foto_sr_sesudah')->store(
                        'transaksi/pengeluaran/' . date('Y/m') . '/sr_sesudah',
                        'public'
                    );
                    $transaksi->update(['foto_sr_sesudah' => $path]);
                }
            }
            
            // Dapatkan detail material lama untuk revert stok
            $oldDetails = DetailTransaksiMaterial::where('transaksi_id', $transaksi->id)->get();
            
            // Revert stok berdasarkan transaksi lama
            if ($transaksi->jenis == 'pengeluaran') {
                foreach ($oldDetails as $oldDetail) {
                    $material = Material::find($oldDetail->material_id);
                    if ($material) {
                        $material->increment('stok', $oldDetail->jumlah);
                    }
                }
            } elseif ($transaksi->jenis == 'penerimaan') {
                foreach ($oldDetails as $oldDetail) {
                    $material = Material::find($oldDetail->material_id);
                    if ($material) {
                        $material->decrement('stok', $oldDetail->jumlah);
                    }
                }
            }
            
            // Delete old details
            DetailTransaksiMaterial::where('transaksi_id', $transaksi->id)->delete();
            
            // Create new details dan update stok
            foreach ($request->material as $item) {
                DetailTransaksiMaterial::create([
                    'transaksi_id' => $transaksi->id,
                    'material_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);
                
                // Update stok berdasarkan jenis transaksi
                $material = Material::find($item['id']);
                if ($material) {
                    if ($transaksi->jenis == 'pengeluaran') {
                        $material->decrement('stok', $item['jumlah']);
                    } elseif ($transaksi->jenis == 'penerimaan') {
                        $material->increment('stok', $item['jumlah']);
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.transaksi.show', $transaksi->id)
                ->with('success', 'Transaksi berhasil diperbarui');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $transaksi = TransaksiMaterial::findOrFail($id);
            
            // Hanya bisa hapus jika status menunggu
            if ($transaksi->status != 'menunggu') {
                return back()->with('error', 'Hanya transaksi dengan status menunggu yang dapat dihapus');
            }
            
            // Dapatkan detail material untuk revert stok
            $details = DetailTransaksiMaterial::where('transaksi_id', $transaksi->id)->get();
            
            // Revert stok berdasarkan jenis transaksi
            if ($transaksi->jenis == 'pengeluaran') {
                foreach ($details as $detail) {
                    $material = Material::find($detail->material_id);
                    if ($material) {
                        $material->increment('stok', $detail->jumlah);
                    }
                }
            } elseif ($transaksi->jenis == 'penerimaan') {
                foreach ($details as $detail) {
                    $material = Material::find($detail->material_id);
                    if ($material) {
                        $material->decrement('stok', $detail->jumlah);
                    }
                }
            }
            
            // Hapus foto-foto dari storage
            if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti)) {
                Storage::disk('public')->delete($transaksi->foto_bukti);
            }
            
            if ($transaksi->jenis == 'pengeluaran') {
                if ($transaksi->foto_sr_sebelum && Storage::disk('public')->exists($transaksi->foto_sr_sebelum)) {
                    Storage::disk('public')->delete($transaksi->foto_sr_sebelum);
                }
                
                if ($transaksi->foto_sr_sesudah && Storage::disk('public')->exists($transaksi->foto_sr_sesudah)) {
                    Storage::disk('public')->delete($transaksi->foto_sr_sesudah);
                }
            }
            
            // Hapus transaksi
            $transaksi->delete();
            
            DB::commit();
            
            return redirect()->route('admin.transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Setujui transaksi
     */
    public function setujui($id, Request $request)
    {
        try {
            $transaksi = TransaksiMaterial::findOrFail($id);
            
            if ($transaksi->status != 'menunggu') {
                return back()->with('error', 'Transaksi sudah diverifikasi sebelumnya');
            }
            
            $transaksi->update([
                'status' => 'disetujui',
                'tanggal_verifikasi' => now(),
                'verifikator_id' => Auth::id(),
            ]);
            
            return back()->with('success', 'Transaksi berhasil disetujui');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Kembalikan transaksi
     */
    public function kembalikan($id, Request $request)
    {
        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $transaksi = TransaksiMaterial::findOrFail($id);
            
            if ($transaksi->status != 'menunggu') {
                return back()->with('error', 'Transaksi sudah diverifikasi sebelumnya');
            }
            
            // Revert stok jika transaksi dikembalikan
            $details = DetailTransaksiMaterial::where('transaksi_id', $transaksi->id)->get();
            
            if ($transaksi->jenis == 'pengeluaran') {
                foreach ($details as $detail) {
                    $material = Material::find($detail->material_id);
                    if ($material) {
                        $material->increment('stok', $detail->jumlah);
                    }
                }
            } elseif ($transaksi->jenis == 'penerimaan') {
                foreach ($details as $detail) {
                    $material = Material::find($detail->material_id);
                    if ($material) {
                        $material->decrement('stok', $detail->jumlah);
                    }
                }
            }
            
            $transaksi->update([
                'status' => 'dikembalikan',
                'alasan_penolakan' => $request->alasan,
                'tanggal_verifikasi' => now(),
                'verifikator_id' => Auth::id(),
            ]);
            
            DB::commit();
            
            return back()->with('success', 'Transaksi berhasil dikembalikan');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate transaction code
     */
    private function generateKodeTransaksi($jenis)
    {
        $prefix = $jenis == 'penerimaan' ? 'TRM' : 'TRK';
        $date = now()->format('ymd');
        
        $count = TransaksiMaterial::where('jenis', $jenis)
            ->whereDate('created_at', now()->toDateString())
            ->count();
        
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return "{$prefix}-{$date}-{$sequence}";
    }
    
    /**
     * Export transaksi to Excel
     */
    public function exportExcel(Request $request)
    {
        // Implementasi export Excel
        return back()->with('success', 'Export Excel berhasil');
    }
    
    /**
     * Export transaksi to PDF
     */
    public function exportPdf(Request $request)
    {
        // Implementasi export PDF
        return back()->with('success', 'Export PDF berhasil');
    }
    
    /**
     * Export transaksi to CSV
     */
    public function exportCsv(Request $request)
    {
        // Implementasi export CSV
        return back()->with('success', 'Export CSV berhasil');
    }
    
    /**
     * Print transaksi list
     */
    public function print(Request $request)
    {
        return view('admin.transaksi.print');
    }
    
    /**
     * Print single transaksi
     */
    public function printSingle($id)
    {
        $transaksi = TransaksiMaterial::findOrFail($id);
        return view('admin.transaksi.print-single', compact('transaksi'));
    }
}