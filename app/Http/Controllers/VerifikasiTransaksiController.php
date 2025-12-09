<?php

namespace App\Http\Controllers;

use App\Models\TransaksiMaterial;
use App\Models\TransaksiMaterialDetail;
use App\Models\VerifikasiTransaksi;
use App\Models\StokMaterial;
use App\Models\Notifikasi;
use App\Models\User;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VerifikasiTransaksiController extends Controller
{
    public function index()
    {
        return view('admin.verifikasi.index');
    }

    public function getData()
    {
        $penerimaan = TransaksiMaterial::with(['details.material', 'user'])
            ->where('jenis', 'penerimaan')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        $pengeluaran = TransaksiMaterial::with(['details.material', 'user'])
            ->where('jenis', 'pengeluaran')
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'penerimaan' => $penerimaan,
            'pengeluaran' => $pengeluaran
        ]);
    }

    public function getDetail($id)
    {
        try {
            $transaksi = TransaksiMaterial::with([
                'details.material', 
                'user',
                'verifikasi.verifier'
            ])->findOrFail($id);

            return response()->json([
                'success' => true,
                'transaksi' => $transaksi,
                'details' => $transaksi->details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Transaksi tidak ditemukan'
            ], 404);
        }
    }

    // Method baru untuk halaman detail
    public function detailPage($type, $id)
    {
        // Validasi type
        if (!in_array($type, ['penerimaan', 'pengeluaran'])) {
            abort(404, 'Tipe transaksi tidak valid');
        }
        
        try {
            // Cari transaksi berdasarkan ID dan jenis
            $transaksi = TransaksiMaterial::with([
                'details.material', 
                'user',
                'verifikasi.verifier'
            ])->where('jenis', $type)
              ->findOrFail($id);
            
            // Data foto dokumentasi (dummy untuk contoh)
            // Anda bisa menyimpan foto di database atau storage
            $dokumentasi = [];
            
            if ($type === 'penerimaan') {
                $dokumentasi = [
                    [
                        'judul' => 'Foto Bukti Penerimaan',
                        'foto' => [
                            '/images/dokumentasi/bukti-penerimaan-1.jpg',
                            '/images/dokumentasi/bukti-penerimaan-2.jpg'
                        ]
                    ]
                ];
            } else {
                $dokumentasi = [
                    [
                        'judul' => 'Foto SR Sebelum',
                        'foto' => [
                            '/images/dokumentasi/sr-sebelum-1.jpg',
                            '/images/dokumentasi/sr-sebelum-2.jpg'
                        ]
                    ],
                    [
                        'judul' => 'Foto SR Setelah',
                        'foto' => [
                            '/images/dokumentasi/sr-setelah-1.jpg',
                            '/images/dokumentasi/sr-setelah-2.jpg'
                        ]
                    ],
                    [
                        'judul' => 'Foto Bukti Penerimaan',
                        'foto' => [
                            '/images/dokumentasi/bukti-penerimaan-pengeluaran-1.jpg'
                        ]
                    ]
                ];
            }
            
            // Format data untuk view - tetap sebagai ARRAY
            $transaksiData = [
                'id' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi,
                'tanggal' => $transaksi->created_at->format('d/m/Y, H:i') . ' WIB',
                'nama' => $transaksi->user->name ?? $transaksi->nama_pihak_transaksi ?? 'Tidak diketahui',
                'jabatan' => $transaksi->user->role ?? 'Petugas',
                'kodeKeperluan' => $transaksi->keperluan ?? 'Tidak ada',
                'keperluan' => $transaksi->keterangan ?? 'Tidak ada',
                'idPelanggan' => $transaksi->id_pelanggan ?? null,
                'alamat' => $transaksi->alamat ?? 'Tidak ada alamat',
                'material' => $transaksi->details->map(function ($detail) {
                    return [
                        'nama' => $detail->material->nama_material ?? 'Tidak diketahui',
                        'kode' => $detail->material->kode_material ?? 'Tidak diketahui',
                        'jumlah' => $detail->jumlah,
                        'satuan' => $detail->material->satuan ?? 'Tidak diketahui',
                    ];
                })->toArray(),
                'dokumentasi' => $dokumentasi,
                'status' => $transaksi->status,
                'jenis' => $transaksi->jenis,
                'catatan' => $transaksi->catatan ?? 'Tidak ada catatan',
            ];
            
        } catch (\Exception $e) {
            // Fallback: data dummy untuk testing jika database error
            $dokumentasi = [];
            
            if ($type === 'penerimaan') {
                $dokumentasi = [
                    [
                        'judul' => 'Foto Bukti Penerimaan',
                        'foto' => [
                            '/images/dokumentasi/bukti-penerimaan-1.jpg',
                            '/images/dokumentasi/bukti-penerimaan-2.jpg'
                        ]
                    ]
                ];
            } else {
                $dokumentasi = [
                    [
                        'judul' => 'Foto SR Sebelum',
                        'foto' => [
                            '/images/dokumentasi/sr-sebelum-1.jpg',
                            '/images/dokumentasi/sr-sebelum-2.jpg'
                        ]
                    ],
                    [
                        'judul' => 'Foto SR Setelah',
                        'foto' => [
                            '/images/dokumentasi/sr-setelah-1.jpg',
                            '/images/dokumentasi/sr-setelah-2.jpg'
                        ]
                    ],
                    [
                        'judul' => 'Foto Bukti Penerimaan',
                        'foto' => [
                            '/images/dokumentasi/bukti-penerimaan-pengeluaran-1.jpg'
                        ]
                    ]
                ];
            }
            
            $transaksiData = [
                'id' => $id,
                'kode_transaksi' => 'TRX-' . strtoupper(substr($type, 0, 3)) . '-' . str_pad($id, 3, '0', STR_PAD_LEFT),
                'tanggal' => now()->format('d/m/Y, H:i') . ' WIB',
                'nama' => 'User Testing',
                'jabatan' => 'Petugas',
                'kodeKeperluan' => $type == 'penerimaan' ? 'YANBUNG' : 'GANGGUAN',
                'keperluan' => 'Testing halaman detail',
                'idPelanggan' => $type == 'pengeluaran' ? 'PLG-1234' : null,
                'alamat' => $type == 'pengeluaran' ? 'Jl. Contoh No. 123, Kota Contoh' : null,
                'material' => [
                    [
                        'nama' => 'Kabel NYY 4x16 mm²',
                        'kode' => 'KBL-001',
                        'jumlah' => 10,
                        'satuan' => 'Roll'
                    ],
                    [
                        'nama' => 'MCB 3P 32A',
                        'kode' => 'MCB-032',
                        'jumlah' => 5,
                        'satuan' => 'Pcs'
                    ]
                ],
                'dokumentasi' => $dokumentasi,
                'status' => 'menunggu',
                'jenis' => $type,
                'catatan' => 'Ini adalah catatan contoh untuk transaksi.',
            ];
        }
        
        return view('admin.verifikasi.detail', [
            'type' => $type,
            'transaksi' => $transaksiData, // Biarkan sebagai array
            'isPenerimaan' => $type == 'penerimaan',
        ]);
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,dikembalikan',
            'alasan_pengembalian' => 'nullable|string|required_if:status,dikembalikan',
        ]);

        DB::beginTransaction();

        try {
            $transaksi = TransaksiMaterial::with(['details.material', 'user'])->findOrFail($id);
            $user = Auth::user();

            // Create verification record
            $verifikasi = VerifikasiTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'diverifikasi_oleh' => $user->id,
                'tanggal_verifikasi' => now(),
                'status' => $request->status,
                'alasan_pengembalian' => $request->alasan_pengembalian,
                'catatan' => $request->alasan_pengembalian,
            ]);

            // Update transaction status
            $transaksi->update([
                'status' => $request->status,
                'tanggal_verifikasi' => now(),
                'diverifikasi_oleh' => $user->id,
            ]);

            // Jika disetujui → update stok
            if ($request->status === 'disetujui') {
                foreach ($transaksi->details as $detail) {
                    $material = $detail->material;

                    if ($transaksi->jenis === 'penerimaan') {
                        // Penerimaan: tambah stok
                        $masuk = $detail->jumlah;
                        $keluar = 0;
                        $stokAkhir = $material->stok + $detail->jumlah;
                        
                        // Update material stock
                        $material->increment('stok', $detail->jumlah);

                    } else {
                        // Pengeluaran: kurangi stok
                        if ($detail->jumlah > $material->stok) {
                            DB::rollBack();
                            return response()->json([
                                'success' => false,
                                'error' => "Stok tidak mencukupi untuk {$material->nama_material}. Stok tersedia: {$material->stok}"
                            ], 422);
                        }

                        $masuk = 0;
                        $keluar = $detail->jumlah;
                        $stokAkhir = $material->stok - $detail->jumlah;
                        
                        // Update material stock
                        $material->decrement('stok', $detail->jumlah);
                    }

                    // Create stock history
                    StokMaterial::create([
                        'material_id' => $material->id,
                        'tanggal' => now(),
                        'masuk' => $masuk,
                        'keluar' => $keluar,
                        'transaksi_id' => $transaksi->id,
                        'stok_akhir' => $stokAkhir,
                        'keterangan' => $transaksi->jenis === 'penerimaan' ? 'Penerimaan disetujui' : 'Pengeluaran disetujui',
                        'user_id' => $user->id,
                    ]);
                }

                // Create notification for user
                if ($transaksi->user_id) {
                    Notifikasi::create([
                        'user_id' => $transaksi->user_id,
                        'transaksi_id' => $transaksi->id,
                        'judul' => 'Transaksi Disetujui',
                        'pesan' => "Transaksi {$transaksi->kode_transaksi} telah disetujui oleh {$user->name}.",
                        'tipe' => 'success',
                        'dibaca' => false,
                    ]);
                }
            } else {
                // Jika dikembalikan
                if ($transaksi->user_id) {
                    Notifikasi::create([
                        'user_id' => $transaksi->user_id,
                        'transaksi_id' => $transaksi->id,
                        'judul' => 'Transaksi Dikembalikan',
                        'pesan' => "Transaksi {$transaksi->kode_transaksi} dikembalikan. Alasan: {$request->alasan_pengembalian}",
                        'tipe' => 'warning',
                        'dibaca' => false,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi berhasil',
                'status' => $request->status,
                'transaksi' => $transaksi
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Verifikasi error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memverifikasi transaksi',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    // Export function (optional)
    public function export(Request $request)
    {
        $query = TransaksiMaterial::with(['user', 'details.material', 'verifikasi.verifier'])
            ->whereIn('status', ['disetujui', 'dikembalikan']);

        if ($request->has('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $transactions,
            'count' => $transactions->count()
        ]);
    }
}