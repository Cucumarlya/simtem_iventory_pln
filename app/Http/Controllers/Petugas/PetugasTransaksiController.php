<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TransaksiMaterial;
use App\Models\DetailTransaksiMaterial;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PetugasTransaksiController extends Controller
{
    // Daftar transaksi penerimaan milik petugas
    public function index()
    {
        try {
            $data = TransaksiMaterial::where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->latest()
                ->paginate(10);

            return view('petugas.transaksi.index', compact('data'));
        } catch (\Exception $e) {
            // Jika tabel belum ada, berikan data contoh
            $data = collect([]);
            return view('petugas.transaksi.index', compact('data'))
                ->with('warning', 'Tabel transaksi belum tersedia. Menampilkan data contoh.');
        }
    }

    // Form tambah penerimaan
    public function create()
    {
        try {
            $materials = Material::where('status', 'aktif')->get();
            return view('petugas.transaksi.create', compact('materials'));
        } catch (\Exception $e) {
            $materials = collect([]);
            return view('petugas.transaksi.create', compact('materials'))
                ->with('warning', 'Data material belum tersedia. Silakan tambah material terlebih dahulu.');
        }
    }

    // Simpan penerimaan
    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama_pihak_transaksi' => 'required|string|max:255',
            'keperluan' => 'required|string',
            'nomor_pelanggan' => 'nullable|string|max:100',
            'material_id' => 'required|array',
            'material_id.*' => 'exists:materials,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:0.01',
        ]);

        DB::beginTransaction();

        try {
            // Generate kode transaksi
            $kode_transaksi = 'TRX-P-' . date('Ymd') . '-' . str_pad(TransaksiMaterial::count() + 1, 4, '0', STR_PAD_LEFT);

            // Buat transaksi utama
            $transaksi = TransaksiMaterial::create([
                'kode_transaksi' => $kode_transaksi,
                'tanggal' => $request->tanggal,
                'jenis' => 'penerimaan',
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan' => $request->keperluan,
                'nomor_pelanggan' => $request->nomor_pelanggan,
                'dibuat_oleh' => Auth::id(),
                'status' => 'menunggu',
                'total' => 0, // Akan diupdate setelah detail transaksi dibuat
            ]);

            // Simpan detail transaksi
            $total_transaksi = 0;
            foreach ($request->material_id as $index => $material_id) {
                $material = Material::find($material_id);
                $jumlah = $request->jumlah[$index];
                $subtotal = $jumlah; // Jika ada harga, bisa dikalikan dengan harga

                DetailTransaksiMaterial::create([
                    'transaksi_material_id' => $transaksi->id,
                    'material_id' => $material_id,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                    'satuan' => $material->satuan ?? 'unit',
                ]);

                $total_transaksi += $subtotal;
            }

            // Update total transaksi
            $transaksi->update(['total' => $total_transaksi]);

            DB::commit();

            return redirect()->route('petugas.transaksi.index')
                ->with('success', 'Penerimaan berhasil ditambahkan dengan kode: ' . $kode_transaksi);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }

    // Detail transaksi
    public function show($id)
    {
        try {
            $transaksi = TransaksiMaterial::with(['details.material', 'creator'])
                ->findOrFail($id);

            // Cek apakah transaksi milik user yang login
            if ($transaksi->dibuat_oleh != Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }

            return view('petugas.transaksi.show', compact('transaksi'));
        } catch (\Exception $e) {
            return redirect()->route('petugas.transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan: ' . $e->getMessage());
        }
    }

    // Form edit transaksi (hanya untuk status menunggu/dikembalikan)
    public function edit($id)
    {
        try {
            $transaksi = TransaksiMaterial::with('details.material')->findOrFail($id);

            // Cek apakah transaksi milik user yang login
            if ($transaksi->dibuat_oleh != Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Cek status transaksi
            if (!in_array($transaksi->status, ['menunggu', 'dikembalikan'])) {
                return redirect()->route('petugas.transaksi.show', $id)
                    ->with('error', 'Transaksi dengan status "' . $transaksi->status . '" tidak dapat diedit.');
            }

            $materials = Material::where('status', 'aktif')->get();
            
            return view('petugas.transaksi.edit', compact('transaksi', 'materials'));
        } catch (\Exception $e) {
            return redirect()->route('petugas.transaksi.index')
                ->with('error', 'Transaksi tidak ditemukan.');
        }
    }

    // Update transaksi
    public function update(Request $request, $id)
    {
        try {
            $transaksi = TransaksiMaterial::findOrFail($id);

            // Cek apakah transaksi milik user yang login
            if ($transaksi->dibuat_oleh != Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Cek status transaksi
            if (!in_array($transaksi->status, ['menunggu', 'dikembalikan'])) {
                return back()->with('error', 'Transaksi tidak dapat diupdate karena status: ' . $transaksi->status);
            }

            $request->validate([
                'tanggal' => 'required|date',
                'nama_pihak_transaksi' => 'required|string|max:255',
                'keperluan' => 'required|string',
                'nomor_pelanggan' => 'nullable|string|max:100',
                'material_id' => 'required|array',
                'material_id.*' => 'exists:materials,id',
                'jumlah' => 'required|array',
                'jumlah.*' => 'numeric|min:0.01',
            ]);

            DB::beginTransaction();

            // Update transaksi utama
            $transaksi->update([
                'tanggal' => $request->tanggal,
                'nama_pihak_transaksi' => $request->nama_pihak_transaksi,
                'keperluan' => $request->keperluan,
                'nomor_pelanggan' => $request->nomor_pelanggan,
                'status' => 'menunggu', // Reset status ke menunggu setelah edit
            ]);

            // Hapus detail lama
            DetailTransaksiMaterial::where('transaksi_material_id', $transaksi->id)->delete();

            // Simpan detail baru
            $total_transaksi = 0;
            foreach ($request->material_id as $index => $material_id) {
                $material = Material::find($material_id);
                $jumlah = $request->jumlah[$index];
                $subtotal = $jumlah; // Jika ada harga, bisa dikalikan dengan harga

                DetailTransaksiMaterial::create([
                    'transaksi_material_id' => $transaksi->id,
                    'material_id' => $material_id,
                    'jumlah' => $jumlah,
                    'subtotal' => $subtotal,
                    'satuan' => $material->satuan ?? 'unit',
                ]);

                $total_transaksi += $subtotal;
            }

            // Update total transaksi
            $transaksi->update(['total' => $total_transaksi]);

            DB::commit();

            return redirect()->route('petugas.transaksi.show', $id)
                ->with('success', 'Transaksi berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Gagal mengupdate transaksi: ' . $e->getMessage());
        }
    }

    // Hapus transaksi (hanya untuk status menunggu/dikembalikan)
    public function destroy($id)
    {
        try {
            $transaksi = TransaksiMaterial::findOrFail($id);

            // Cek apakah transaksi milik user yang login
            if ($transaksi->dibuat_oleh != Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // Cek status transaksi
            if (!in_array($transaksi->status, ['menunggu', 'dikembalikan'])) {
                return back()->with('error', 'Tidak dapat menghapus transaksi dengan status: ' . $transaksi->status);
            }

            DB::beginTransaction();

            // Hapus detail transaksi
            DetailTransaksiMaterial::where('transaksi_material_id', $transaksi->id)->delete();
            
            // Hapus transaksi
            $transaksi->delete();

            DB::commit();

            return redirect()->route('petugas.transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus transaksi: ' . $e->getMessage());
        }
    }

    // Download PDF transaksi
    public function downloadPdf($id)
    {
        try {
            $transaksi = TransaksiMaterial::with(['details.material', 'creator'])
                ->findOrFail($id);

            if ($transaksi->dibuat_oleh != Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke transaksi ini.');
            }

            // In a real app, you would generate PDF here
            return response()->streamDownload(function () use ($transaksi) {
                echo "Laporan Transaksi: " . $transaksi->kode_transaksi . "\n";
                echo "Tanggal: " . $transaksi->tanggal . "\n";
                echo "Penerima: " . $transaksi->nama_pihak_transaksi . "\n";
                echo "Keperluan: " . $transaksi->keperluan . "\n";
                echo "Status: " . $transaksi->status . "\n\n";
                echo "Detail Material:\n";
                foreach ($transaksi->details as $detail) {
                    echo "- " . ($detail->material->nama_material ?? 'Material') . 
                         ": " . $detail->jumlah . " " . $detail->satuan . "\n";
                }
            }, 'transaksi-' . $transaksi->kode_transaksi . '.txt');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate laporan: ' . $e->getMessage());
        }
    }

    // Get transaksi untuk dashboard
    public function getRecentTransactions()
    {
        try {
            $transactions = TransaksiMaterial::where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['id', 'kode_transaksi', 'nama_pihak_transaksi', 'keperluan', 'status', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}