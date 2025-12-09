<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\TransaksiMaterial;
use App\Models\Material;
use App\Models\DetailTransaksiMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaanController extends Controller
{
    /**
     * Constructor dengan middleware
     */
    public function __construct()
    {
        // Kosongkan constructor karena middleware sudah di-handle di route
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Log untuk debugging
            \Log::info('PenerimaanController@index diakses', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'role' => Auth::user()->role
            ]);

            $query = TransaksiMaterial::with(['user'])
                ->where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->orderBy('created_at', 'desc');

            // Filter pencarian
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                      ->orWhere('nama_pihak_transaksi', 'like', "%{$search}%")
                      ->orWhere('keperluan', 'like', "%{$search}%");
                });
            }

            // Filter tanggal
            if ($request->has('tanggal') && $request->tanggal != '') {
                $query->whereDate('tanggal', $request->tanggal);
            }

            // Filter status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $penerimaan = $query->paginate(15);

            // Debug data
            \Log::debug('Data penerimaan ditemukan: ' . $penerimaan->count() . ' record');

            return view('petugas.penerimaan.index', compact('penerimaan'));
            
        } catch (\Exception $e) {
            \Log::error('Error di PenerimaanController@index: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            \Log::info('PenerimaanController@create diakses', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name
            ]);
            
            // PERBAIKAN: Hapus filter status karena kolom tidak ada
            $materials = Material::orderBy('nama_material')->get();
            
            return view('petugas.penerimaan.create', compact('materials'));
            
        } catch (\Exception $e) {
            \Log::error('Error di PenerimaanController@create: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            \Log::info('PenerimaanController@store diakses', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'data' => $request->all()
            ]);

            // Validasi yang DIPERBAIKI
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'nama_penerima' => 'required|string|max:255', // PERBAIKAN: nama_penerima bukan nama_pihak_transaksi
                'keperluan' => 'required|in:YANBUNG,P2TL,GANGGUAN,PLN',
                'material' => 'required|array|min:1',
                'material.*.nama' => 'required|string',
                'material.*.jumlah' => 'required|integer|min:1',
                'foto_bukti' => 'required|image|max:5120',
            ], [
                'material.required' => 'Minimal tambahkan 1 material',
                'material.*.nama.required' => 'Material harus dipilih',
                'material.*.jumlah.required' => 'Jumlah harus diisi',
                'material.*.jumlah.min' => 'Jumlah minimal 1',
                'foto_bukti.required' => 'Foto bukti penerimaan wajib diupload',
                'foto_bukti.image' => 'File harus berupa gambar',
                'foto_bukti.max' => 'Ukuran file maksimal 5MB',
            ]);

            DB::beginTransaction();

            // Generate kode transaksi unik
            $date = Carbon::now()->format('ymd');
            $count = TransaksiMaterial::whereDate('created_at', Carbon::today())->count() + 1;
            $kodeTransaksi = 'PM-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // Upload foto bukti
            $fotoBukti = $request->file('foto_bukti')->store('transaksi/penerimaan/bukti', 'public');

            // Buat transaksi penerimaan
            $transaksi = TransaksiMaterial::create([
                'kode_transaksi' => $kodeTransaksi,
                'tanggal' => $validated['tanggal'],
                'jenis' => 'penerimaan',
                'nama_pihak_transaksi' => $validated['nama_penerima'], // PERBAIKAN: Map ke kolom yang benar
                'keperluan' => $validated['keperluan'],
                'foto_bukti' => $fotoBukti,
                'dibuat_oleh' => Auth::id(),
                'status' => 'menunggu',
            ]);

            // Simpan detail materials
            foreach ($validated['material'] as $materialData) {
                // Cari material berdasarkan nama
                $material = Material::where('nama_material', $materialData['nama'])->first();
                
                if ($material) {
                    DetailTransaksiMaterial::create([
                        'transaksi_id' => $transaksi->id,
                        'material_id' => $material->id,
                        'jumlah' => $materialData['jumlah'],
                    ]);
                }
            }

            DB::commit();

            \Log::info('Penerimaan berhasil dibuat', [
                'kode_transaksi' => $kodeTransaksi,
                'transaksi_id' => $transaksi->id
            ]);

            return redirect()->route('petugas.penerimaan.index')
                ->with('success', 'Penerimaan berhasil ditambahkan dengan kode ' . $kodeTransaksi . ' dan menunggu verifikasi.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error storing penerimaan: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            \Log::info('PenerimaanController@show diakses', [
                'user_id' => Auth::id(),
                'transaksi_id' => $id
            ]);

            $penerimaan = TransaksiMaterial::with(['details.material', 'user'])
                ->where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->findOrFail($id);

            return view('petugas.penerimaan.show', compact('penerimaan'));
            
        } catch (\Exception $e) {
            \Log::error('Error di PenerimaanController@show: ' . $e->getMessage());
            return back()->with('error', 'Data tidak ditemukan atau Anda tidak memiliki akses.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            \Log::info('PenerimaanController@edit diakses', [
                'user_id' => Auth::id(),
                'transaksi_id' => $id
            ]);

            $penerimaan = TransaksiMaterial::with(['details.material'])
                ->where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->where('status', 'dikembalikan')
                ->findOrFail($id);

            // PERBAIKAN: Hapus filter status
            $materials = Material::orderBy('nama_material')->get();

            return view('petugas.penerimaan.edit', compact('penerimaan', 'materials'));
            
        } catch (\Exception $e) {
            \Log::error('Error di PenerimaanController@edit: ' . $e->getMessage());
            return back()->with('error', 'Data tidak ditemukan, status tidak dikembalikan, atau Anda tidak memiliki akses.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            \Log::info('PenerimaanController@update diakses', [
                'user_id' => Auth::id(),
                'transaksi_id' => $id,
                'data' => $request->all()
            ]);

            $transaksi = TransaksiMaterial::where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->where('status', 'dikembalikan')
                ->findOrFail($id);

            // PERBAIKAN: Update validation rules
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'nama_pihak_transaksi' => 'required|string|max:255',
                'keperluan' => 'required|in:YANBUNG,P2TL,GANGGUAN,PLN',
                'materials' => 'required|array|min:1',
                'materials.*.material_id' => 'required|exists:materials,id',
                'materials.*.jumlah' => 'required|integer|min:1',
                'foto_bukti' => 'nullable|image|max:5120',
                'catatan_perbaikan' => 'nullable|string|max:1000',
            ], [
                'materials.required' => 'Minimal tambahkan 1 material',
                'materials.*.material_id.required' => 'Material harus dipilih',
                'materials.*.jumlah.required' => 'Jumlah harus diisi',
                'materials.*.jumlah.min' => 'Jumlah minimal 1',
            ]);

            DB::beginTransaction();

            // Update transaksi
            $transaksi->update([
                'tanggal' => $validated['tanggal'],
                'nama_pihak_transaksi' => $validated['nama_pihak_transaksi'],
                'keperluan' => $validated['keperluan'],
                'status' => 'menunggu', // Kembali ke status menunggu
            ]);

            // Update foto jika ada
            if ($request->hasFile('foto_bukti')) {
                // Hapus foto lama
                if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti)) {
                    Storage::disk('public')->delete($transaksi->foto_bukti);
                }
                // Upload foto baru
                $transaksi->foto_bukti = $request->file('foto_bukti')->store('transaksi/penerimaan/bukti', 'public');
                $transaksi->save();
            }

            // Hapus detail lama
            $transaksi->details()->delete();

            // Simpan detail baru
            foreach ($validated['materials'] as $materialData) {
                DetailTransaksiMaterial::create([
                    'transaksi_id' => $transaksi->id,
                    'material_id' => $materialData['material_id'],
                    'jumlah' => $materialData['jumlah'],
                ]);
            }

            DB::commit();

            \Log::info('Penerimaan berhasil diupdate', [
                'transaksi_id' => $transaksi->id,
                'kode_transaksi' => $transaksi->kode_transaksi
            ]);

            return redirect()->route('petugas.penerimaan.show', $transaksi->id)
                ->with('success', 'Penerimaan berhasil diperbarui dan menunggu verifikasi ulang.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating penerimaan: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            \Log::info('PenerimaanController@destroy diakses', [
                'user_id' => Auth::id(),
                'transaksi_id' => $id
            ]);

            $transaksi = TransaksiMaterial::where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->where('status', 'dikembalikan')
                ->findOrFail($id);

            DB::beginTransaction();

            // Hapus foto
            if ($transaksi->foto_bukti && Storage::disk('public')->exists($transaksi->foto_bukti)) {
                Storage::disk('public')->delete($transaksi->foto_bukti);
            }

            // Hapus detail
            $transaksi->details()->delete();
            
            // Hapus transaksi
            $kodeTransaksi = $transaksi->kode_transaksi;
            $transaksi->delete();

            DB::commit();

            \Log::info('Penerimaan berhasil dihapus', [
                'transaksi_id' => $id,
                'kode_transaksi' => $kodeTransaksi
            ]);

            return redirect()->route('petugas.penerimaan.index')
                ->with('success', 'Penerimaan ' . $kodeTransaksi . ' berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting penerimaan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Export data to Excel.
     */
    public function exportExcel(Request $request)
    {
        try {
            \Log::info('PenerimaanController@exportExcel diakses', [
                'user_id' => Auth::id()
            ]);

            $query = TransaksiMaterial::where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->orderBy('created_at', 'desc');

            // Apply filters
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode_transaksi', 'like', "%{$search}%")
                      ->orWhere('nama_pihak_transaksi', 'like', "%{$search}%")
                      ->orWhere('keperluan', 'like', "%{$search}%");
                });
            }

            if ($request->has('tanggal') && $request->tanggal != '') {
                $query->whereDate('tanggal', $request->tanggal);
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            $penerimaan = $query->get();

            // Generate Excel
            $filename = 'penerimaan-material-' . date('Y-m-d-H-i') . '.xlsx';
            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            return response()->streamDownload(function() use ($penerimaan) {
                $output = fopen('php://output', 'w');
                
                // Header
                fputcsv($output, [
                    'Kode Transaksi',
                    'Tanggal',
                    'Nama Penerima',
                    'Keperluan',
                    'Status',
                    'Dibuat Oleh',
                    'Tanggal Dibuat'
                ], ';');
                
                // Data
                foreach ($penerimaan as $item) {
                    fputcsv($output, [
                        $item->kode_transaksi,
                        $item->tanggal,
                        $item->nama_pihak_transaksi,
                        $item->keperluan,
                        $item->status,
                        $item->user->name ?? '-',
                        $item->created_at->format('d-m-Y H:i')
                    ], ';');
                }
                
                fclose($output);
            }, $filename, $headers);
            
        } catch (\Exception $e) {
            \Log::error('Error exporting penerimaan: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Print transaksi.
     */
    public function print($id)
    {
        try {
            \Log::info('PenerimaanController@print diakses', [
                'user_id' => Auth::id(),
                'transaksi_id' => $id
            ]);

            $penerimaan = TransaksiMaterial::with(['details.material', 'user'])
                ->where('dibuat_oleh', Auth::id())
                ->where('jenis', 'penerimaan')
                ->findOrFail($id);

            return view('petugas.penerimaan.print', compact('penerimaan'));
            
        } catch (\Exception $e) {
            \Log::error('Error printing penerimaan: ' . $e->getMessage());
            return back()->with('error', 'Gagal mencetak data: ' . $e->getMessage());
        }
    }
    
    /**
     * Debug method untuk cek user info.
     */
    public function debugUserInfo()
    {
        $user = Auth::user();
        return response()->json([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role ?? 'NULL',
            'is_petugas' => $user->role === 'petugas',
            'has_role_method' => method_exists($user, 'hasRole'),
            'role_relation_exists' => method_exists($user, 'role'),
            'all_attributes' => $user->getAttributes()
        ]);
    }
}
