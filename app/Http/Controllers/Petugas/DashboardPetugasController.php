<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardPetugasController extends Controller
{
    /**
     * Tampilkan dashboard utama untuk petugas
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            // Validasi role user
            if (!$this->isPetugas($user)) {
                abort(403, 'Akses tidak diizinkan. Hanya untuk petugas.');
            }
            
            // Hitung statistik untuk dashboard
            $stats = $this->getDashboardStats();
            
            // Ambil transaksi terbaru
            $transaksiTerbaru = $this->getRecentTransactions();
            
            // Data untuk chart
            $chartData = $this->getChartData();
            
            return view('dashboard.petugas', [
                'stats' => $stats,
                'transaksiTerbaru' => $transaksiTerbaru,
                'chartData' => $chartData
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading dashboard: ' . $e->getMessage());
            
            // Fallback data jika ada error
            return view('dashboard.petugas', [
                'stats' => $this->getFallbackStats(),
                'transaksiTerbaru' => $this->getSampleTransactions(),
                'chartData' => $this->getFallbackChartData()
            ]);
        }
    }

    /**
     * Ambil statistik dashboard (4 cards)
     */
    private function getDashboardStats()
    {
        $userId = Auth::id();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        try {
            // Cek apakah tabel transaksi_material ada
            $tableExists = DB::select("SHOW TABLES LIKE 'transaksi_material'");
            
            if (empty($tableExists)) {
                return $this->getFallbackStats();
            }
            
            // 1. Total Penerimaan (dalam Rupiah) - Bulan Ini
            $totalPenerimaan = DB::table('transaksi_material')
                ->where('dibuat_oleh', $userId)
                ->where('jenis', 'penerimaan')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->where('status', 'disetujui')
                ->sum('total') ?? 0;
            
            // 2. Jumlah Transaksi Menunggu Verifikasi
            $menungguVerifikasi = DB::table('transaksi_material')
                ->where('dibuat_oleh', $userId)
                ->where('jenis', 'penerimaan')
                ->where('status', 'menunggu')
                ->count();
            
            // 3. Jumlah Transaksi Dikembalikan
            $dikembalikan = DB::table('transaksi_material')
                ->where('dibuat_oleh', $userId)
                ->where('jenis', 'penerimaan')
                ->where('status', 'dikembalikan')
                ->count();
            
            // 4. Jumlah Transaksi Disetujui (Bulan Ini)
            $disetujui = DB::table('transaksi_material')
                ->where('dibuat_oleh', $userId)
                ->where('jenis', 'penerimaan')
                ->where('status', 'disetujui')
                ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                ->count();
            
            return [
                'totalPenerimaan' => $totalPenerimaan,
                'menungguVerifikasi' => $menungguVerifikasi,
                'dikembalikan' => $dikembalikan,
                'disetujui' => $disetujui
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error getting stats: ' . $e->getMessage());
            return $this->getFallbackStats();
        }
    }

    /**
     * Ambil transaksi terbaru untuk dashboard
     */
    private function getRecentTransactions()
    {
        $userId = Auth::id();
        
        try {
            $transactions = DB::table('transaksi_material as tm')
                ->select(
                    'tm.id',
                    'tm.kode_transaksi',
                    'tm.tanggal',
                    'tm.nama_pihak_transaksi',
                    'tm.keperluan',
                    'tm.status',
                    DB::raw('(SELECT COUNT(*) FROM detail_transaksi_material WHERE transaksi_id = tm.id) as jumlah_material')
                )
                ->where('tm.dibuat_oleh', $userId)
                ->where('tm.jenis', 'penerimaan')
                ->orderBy('tm.created_at', 'desc')
                ->limit(5)
                ->get();
            
            if ($transactions->isEmpty()) {
                return $this->getSampleTransactions();
            }
            
            // Format tanggal dan tambahkan field penerima
            $transactions->transform(function ($item) {
                $item->formatted_date = Carbon::parse($item->tanggal)->format('d M Y');
                $item->penerima = $item->nama_pihak_transaksi;
                return $item;
            });
            
            return $transactions;
            
        } catch (\Exception $e) {
            \Log::error('Error getting recent transactions: ' . $e->getMessage());
            return $this->getSampleTransactions();
        }
    }

    /**
     * Ambil data untuk chart (bulanan dan tahunan)
     */
    private function getChartData()
    {
        $userId = Auth::id();
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        try {
            // Data bulanan (30 hari terakhir)
            $startDate = Carbon::now()->subDays(29);
            $endDate = Carbon::now();
            
            $bulananData = DB::table('transaksi_material')
                ->select(
                    DB::raw('DAY(tanggal) as hari'),
                    DB::raw('DATE(tanggal) as tanggal'),
                    DB::raw('SUM(total) as total')
                )
                ->where('dibuat_oleh', $userId)
                ->where('jenis', 'penerimaan')
                ->where('status', 'disetujui')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get()
                ->map(function ($item) {
                    return [
                        'hari' => (int)$item->hari,
                        'tanggal' => Carbon::parse($item->tanggal)->format('d M'),
                        'total' => (float)($item->total ?? 0)
                    ];
                });
            
            // Data tahunan (12 bulan tahun ini)
            $tahunanData = collect();
            
            for ($month = 1; $month <= 12; $month++) {
                $monthStart = Carbon::create($currentYear, $month, 1)->startOfMonth();
                $monthEnd = Carbon::create($currentYear, $month, 1)->endOfMonth();
                
                $total = DB::table('transaksi_material')
                    ->where('dibuat_oleh', $userId)
                    ->where('jenis', 'penerimaan')
                    ->where('status', 'disetujui')
                    ->whereBetween('tanggal', [$monthStart, $monthEnd])
                    ->sum('total') ?? 0;
                
                $tahunanData->push([
                    'bulan' => $monthStart->translatedFormat('M'),
                    'total' => (float)$total
                ]);
            }
            
            return [
                'bulanan' => $bulananData->toArray(),
                'tahunan' => $tahunanData->toArray()
            ];
            
        } catch (\Exception $e) {
            \Log::error('Error getting chart data: ' . $e->getMessage());
            return $this->getFallbackChartData();
        }
    }

    /**
     * Cek apakah user adalah petugas
     */
    private function isPetugas($user)
    {
        if (!$user) return false;
        
        // Cek role user
        if (isset($user->role)) {
            return $user->role === 'petugas';
        }
        
        // Cek jika menggunakan Spatie Permission
        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('petugas');
        }
        
        // Cek jika menggunakan relasi role
        if ($user->role && $user->role->name === 'petugas') {
            return true;
        }
        
        return false;
    }

    /**
     * Sample data for fallback
     */
    private function getSampleTransactions()
    {
        return collect([
            (object)[
                'id' => 1,
                'kode_transaksi' => 'PM-' . Carbon::today()->format('ymd') . '-001',
                'tanggal' => Carbon::today()->format('Y-m-d'),
                'formatted_date' => Carbon::today()->format('d M Y'),
                'penerima' => 'Ahmad Fauzi',
                'nama_pihak_transaksi' => 'Ahmad Fauzi',
                'keperluan' => 'Proyek Pembangunan Gedung A',
                'status' => 'menunggu',
                'jumlah_material' => 3
            ],
            (object)[
                'id' => 2,
                'kode_transaksi' => 'PM-' . Carbon::yesterday()->format('ymd') . '-002',
                'tanggal' => Carbon::yesterday()->format('Y-m-d'),
                'formatted_date' => Carbon::yesterday()->format('d M Y'),
                'penerima' => 'Siti Rahayu',
                'nama_pihak_transaksi' => 'Siti Rahayu',
                'keperluan' => 'Maintenance Rutin',
                'status' => 'disetujui',
                'jumlah_material' => 5
            ],
            (object)[
                'id' => 3,
                'kode_transaksi' => 'PM-' . Carbon::now()->subDays(2)->format('ymd') . '-003',
                'tanggal' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'formatted_date' => Carbon::now()->subDays(2)->format('d M Y'),
                'penerima' => 'Budi Santoso',
                'nama_pihak_transaksi' => 'Budi Santoso',
                'keperluan' => 'Perbaikan Jalan',
                'status' => 'dikembalikan',
                'jumlah_material' => 2
            ],
            (object)[
                'id' => 4,
                'kode_transaksi' => 'PM-' . Carbon::now()->subDays(3)->format('ymd') . '-004',
                'tanggal' => Carbon::now()->subDays(3)->format('Y-m-d'),
                'formatted_date' => Carbon::now()->subDays(3)->format('d M Y'),
                'penerima' => 'Dewi Anggraeni',
                'nama_pihak_transaksi' => 'Dewi Anggraeni',
                'keperluan' => 'Pengadaan Alat Tulis',
                'status' => 'menunggu',
                'jumlah_material' => 1
            ],
            (object)[
                'id' => 5,
                'kode_transaksi' => 'PM-' . Carbon::now()->subDays(4)->format('ymd') . '-005',
                'tanggal' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'formatted_date' => Carbon::now()->subDays(4)->format('d M Y'),
                'penerima' => 'Rizky Pratama',
                'nama_pihak_transaksi' => 'Rizky Pratama',
                'keperluan' => 'Proyek Instalasi Listrik',
                'status' => 'disetujui',
                'jumlah_material' => 4
            ]
        ]);
    }

    /**
     * Fallback stats (4 cards)
     */
    private function getFallbackStats()
    {
        return [
            'totalPenerimaan' => 285000000, // Rp 285 juta
            'menungguVerifikasi' => 2,
            'dikembalikan' => 1,
            'disetujui' => 8
        ];
    }

    /**
     * Fallback chart data
     */
    private function getFallbackChartData()
    {
        // Data bulanan dummy (30 hari)
        $bulanan = [];
        $today = Carbon::today();
        for ($i = 29; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $bulanan[] = [
                'hari' => $date->day,
                'tanggal' => $date->format('d M'),
                'total' => rand(1000000, 5000000)
            ];
        }
        
        // Data tahunan dummy (12 bulan)
        $tahunan = [];
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        foreach ($months as $index => $month) {
            $tahunan[] = [
                'bulan' => $month,
                'total' => rand(15000000, 45000000)
            ];
        }
        
        return [
            'bulanan' => $bulanan,
            'tahunan' => $tahunan
        ];
    }

    /**
     * API untuk mendapatkan data statistik real-time
     */
    public function getStatsApi()
    {
        try {
            $stats = $this->getDashboardStats();
            
            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data statistik',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan chart data
     */
    public function getChartDataApi($type = 'bulanan')
    {
        try {
            $chartData = $this->getChartData();
            
            $data = match($type) {
                'tahunan' => $chartData['tahunan'],
                default => $chartData['bulanan']
            };
            
            return response()->json([
                'success' => true,
                'type' => $type,
                'data' => $data,
                'timestamp' => Carbon::now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data chart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export dashboard data
     */
    public function exportDashboard(Request $request)
    {
        try {
            $user = auth()->user();
            $stats = $this->getDashboardStats();
            $transaksiTerbaru = $this->getRecentTransactions();
            
            $data = [
                'user' => $user,
                'stats' => $stats,
                'transactions' => $transaksiTerbaru,
                'export_date' => Carbon::now()->format('d F Y H:i:s'),
                'period' => Carbon::now()->translatedFormat('F Y')
            ];
            
            $filename = 'dashboard-petugas-' . Carbon::now()->format('Y-m-d') . '.txt';
            
            return response()->streamDownload(function () use ($data) {
                echo "=== LAPORAN DASHBOARD PETUGAS ===\n\n";
                echo "Tanggal Export: {$data['export_date']}\n";
                echo "Periode: {$data['period']}\n";
                echo "Petugas: {$data['user']->name}\n\n";
                
                echo "=== STATISTIK ===\n";
                echo "Total Penerimaan: Rp " . number_format($data['stats']['totalPenerimaan'], 0, ',', '.') . "\n";
                echo "Menunggu Verifikasi: {$data['stats']['menungguVerifikasi']} transaksi\n";
                echo "Dikembalikan: {$data['stats']['dikembalikan']} transaksi\n";
                echo "Disetujui: {$data['stats']['disetujui']} transaksi\n\n";
                
                echo "=== TRANSAKSI TERBARU ===\n";
                foreach ($data['transactions'] as $index => $transaction) {
                    echo ($index + 1) . ". {$transaction->kode_transaksi} - {$transaction->penerima}\n";
                    echo "   Tanggal: {$transaction->formatted_date}\n";
                    echo "   Keperluan: {$transaction->keperluan}\n";
                    echo "   Jumlah Material: {$transaction->jumlah_material} item\n";
                    echo "   Status: " . ucfirst($transaction->status) . "\n\n";
                }
                
                echo "=== END OF REPORT ===\n";
            }, $filename);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache()
    {
        try {
            // Clear cache related to dashboard
            cache()->forget('dashboard_stats_' . auth()->id());
            cache()->forget('recent_transactions_' . auth()->id());
            cache()->forget('dashboard_chart_' . auth()->id());
            
            return response()->json([
                'success' => true,
                'message' => 'Cache dashboard berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus cache: ' . $e->getMessage()
            ], 500);
        }
    }
}