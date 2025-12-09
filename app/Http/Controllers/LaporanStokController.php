<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\TransaksiMaterialDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapStokExport;

class LaporanStokController extends Controller
{
    /**
     * TAMPILKAN REKAP STOK (VIEW)
     */
    public function rekap(Request $request)
    {
        try {
            // Ambil semua material dengan stok
            $materials = Material::orderBy('nama_material')->get();
            
            // Hitung stok aktual dari transaksi
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
                
                // Nilai default untuk kolom lainnya
                $material->pengeluaran_yanbung = 0;
                $material->pengeluaran_p2tl = 0;
                $material->pengeluaran_gangguan = 0;
                $material->pln_in_transit_penerimaan = 0;
                $material->pln_in_transit_pengeluaran = 0;
                $material->mms = 0; // Minimum Stock Level
                $material->koef_selisih = 0;
                $material->sisa_real = 0;
                $material->tanggal_stok_lebih = null;
                $material->waktu_stok_lebih = null;
            }
            
            // PERBAIKAN: Sesuaikan path view dengan struktur folder Anda
            return view('admin.rekap_stok.index', compact('materials'));
            
        } catch (\Exception $e) {
            \Log::error('Error in LaporanStokController@rekap: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * DETAIL MATERIAL
     */
    public function detail($materialId)
    {
        try {
            $material = Material::findOrFail($materialId);
            
            // Hitung stok aktual
            $totalPenerimaan = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                $q->where('jenis', 'penerimaan')->where('status', 'disetujui');
            })->where('material_id', $material->id)->sum('jumlah');
            
            $totalPengeluaran = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                $q->where('jenis', 'pengeluaran')->where('status', 'disetujui');
            })->where('material_id', $material->id)->sum('jumlah');
            
            $material->stok_aktual = $material->stok + $totalPenerimaan - $totalPengeluaran;
            $material->total_penerimaan = $totalPenerimaan;
            $material->total_pengeluaran = $totalPengeluaran;
            
            // Ambil transaksi terkait
            $transaksiDetails = TransaksiMaterialDetail::with(['transaksi', 'transaksi.user'])
                ->where('material_id', $materialId)
                ->whereHas('transaksi', function($q) {
                    $q->where('status', 'disetujui');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            // PERBAIKAN: Sesuaikan path view
            return view('admin.rekap_stok.detail', compact('material', 'transaksiDetails'));
            
        } catch (\Exception $e) {
            \Log::error('Error in LaporanStokController@detail: ' . $e->getMessage());
            return redirect()->route('admin.rekap-stok.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * EXPORT PDF LAPORAN REKAP STOK
     */
    public function exportPdf(Request $request)
    {
        try {
            $materials = Material::orderBy('nama_material')->get();
            
            // Hitung stok aktual
            foreach ($materials as $material) {
                $totalPenerimaan = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                    $q->where('jenis', 'penerimaan')->where('status', 'disetujui');
                })->where('material_id', $material->id)->sum('jumlah');
                
                $totalPengeluaran = TransaksiMaterialDetail::whereHas('transaksi', function($q) {
                    $q->where('jenis', 'pengeluaran')->where('status', 'disetujui');
                })->where('material_id', $material->id)->sum('jumlah');
                
                $material->stok_aktual = $material->stok + $totalPenerimaan - $totalPengeluaran;
            }
            
            // PERBAIKAN: Sesuaikan path view PDF
            $pdf = Pdf::loadView('admin.rekap_stok.pdf', compact('materials'))
                ->setPaper('A4', 'landscape');

            return $pdf->download('rekap_stok_' . date('Y-m-d') . '.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Error in LaporanStokController@exportPdf: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor PDF: ' . $e->getMessage());
        }
    }

    /**
     * EXPORT EXCEL LAPORAN REKAP STOK
     */
    public function exportExcel(Request $request)
    {
        try {
            return Excel::download(new RekapStokExport(), 'rekap_stok_' . date('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            \Log::error('Error in LaporanStokController@exportExcel: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor Excel: ' . $e->getMessage());
        }
    }

    /**
     * EXPORT CSV LAPORAN REKAP STOK
     */
    public function exportCsv(Request $request)
    {
        try {
            return Excel::download(new RekapStokExport(), 'rekap_stok_' . date('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
        } catch (\Exception $e) {
            \Log::error('Error in LaporanStokController@exportCsv: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengekspor CSV: ' . $e->getMessage());
        }
    }

    /**
     * API untuk mendapatkan data rekap stok (untuk AJAX)
     */
    public function getData(Request $request)
    {
        try {
            $materials = Material::orderBy('nama_material');
            
            // Search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $materials->where(function($q) use ($search) {
                    $q->where('nama_material', 'like', "%{$search}%")
                      ->orWhere('kode_material', 'like', "%{$search}%");
                });
            }
            
            $materials = $materials->get();
            
            // Hitung stok aktual
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
                
                // Nilai default untuk kolom lainnya
                $material->pengeluaran_yanbung = 0;
                $material->pengeluaran_p2tl = 0;
                $material->pengeluaran_gangguan = 0;
                $material->pln_in_transit_penerimaan = 0;
                $material->pln_in_transit_pengeluaran = 0;
                $material->mms = 0;
                $material->koef_selisih = 0;
                $material->sisa_real = 0;
            }

            return response()->json([
                'success' => true,
                'data' => $materials,
                'total' => $materials->count(),
                'last_update' => now()->format('d/m/Y H:i')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
}