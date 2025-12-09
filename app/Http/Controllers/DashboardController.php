<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user || !$user->role) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'User tidak memiliki role yang valid.'
            ]);
        }

        return match ($user->role->name) {
            'admin'           => redirect()->route('dashboard.admin'),
            'petugas'         => redirect()->route('petugas.dashboard'), // DIUBAH DI SINI
            'petugas_yanbung' => redirect()->route('dashboard.petugas_yanbung'),
            default           => abort(403, 'Role tidak dikenali.'),
        };
    }

    public function admin()
    {
        $user = auth()->user();
        
        if (!$user->role || $user->role->name !== 'admin') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('dashboard.admin', [
            'user' => $user
        ]);
    }

    public function petugas()
    {
        // Method ini sekarang tidak digunakan karena sudah ada DashboardPetugasController
        // Tapi tetap dipertahankan untuk kompatibilitas
        $user = auth()->user();
        
        if (!$user->role || $user->role->name !== 'petugas') {
            abort(403, 'Unauthorized access.');
        }
        
        // Redirect ke DashboardPetugasController
        return redirect()->route('petugas.dashboard');
    }

    public function petugas_yanbung()
    {
        $user = auth()->user();
        
        if (!$user->role || $user->role->name !== 'petugas_yanbung') {
            abort(403, 'Unauthorized access.');
        }
        
        return view('dashboard.petugas_yanbung', [
            'user' => $user
        ]);
    }
    
    /**
     * Get statistics for petugas dashboard
     */
    private function getStats()
    {
        $today = now()->format('Y-m-d');
        $monthStart = now()->startOfMonth()->format('Y-m-d');
        $monthEnd = now()->endOfMonth()->format('Y-m-d');
        
        try {
            // Jika tabel penerimaan ada
            $penerimaanHariIni = DB::table('penerimaan')
                ->whereDate('created_at', $today)
                ->sum('total') ?? 0;
                
            $penerimaanBulanIni = DB::table('penerimaan')
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total') ?? 0;
                
            $materialMasuk = DB::table('penerimaan_detail')
                ->whereDate('created_at', $today)
                ->sum('quantity') ?? 0;
                
            $notifikasiBaru = DB::table('notifications')
                ->where('read_at', null)
                ->whereDate('created_at', $today)
                ->count() ?? 0;
        } catch (\Exception $e) {
            // Jika tabel belum ada, berikan nilai default
            $penerimaanHariIni = 12500000; // Contoh data
            $penerimaanBulanIni = 285000000;
            $materialMasuk = 15;
            $notifikasiBaru = 3;
        }
        
        return [
            'penerimaanHariIni' => $penerimaanHariIni,
            'penerimaanBulanIni' => $penerimaanBulanIni,
            'materialMasuk' => $materialMasuk,
            'notifikasiBaru' => $notifikasiBaru,
        ];
    }
    
    /**
     * Get latest transactions for petugas dashboard
     */
    private function getTransaksiTerbaru()
    {
        try {
            // Coba ambil dari tabel transaksi atau penerimaan
            $transaksi = DB::table('transaksi')
                ->select('id', 'penerima', 'keterangan', 'status', 'created_at')
                ->where('tipe', 'penerimaan')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            // Jika tidak ada data, berikan contoh data
            if ($transaksi->isEmpty()) {
                return $this->getSampleTransaksi();
            }
            
            return $transaksi;
        } catch (\Exception $e) {
            // Berikan data contoh jika tabel belum ada
            return $this->getSampleTransaksi();
        }
    }
    
    /**
     * Get sample transaction data for demo
     */
    private function getSampleTransaksi()
    {
        return collect([
            (object)[
                'id' => 1,
                'penerima' => 'Ahmad Fauzi',
                'keterangan' => 'Proyek Pembangunan Gedung A',
                'status' => 'menunggu',
                'created_at' => now()->subDays(1)
            ],
            (object)[
                'id' => 2,
                'penerima' => 'Siti Rahayu',
                'keterangan' => 'Maintenance Rutin',
                'status' => 'disetujui',
                'created_at' => now()->subDays(2)
            ],
            (object)[
                'id' => 3,
                'penerima' => 'Budi Santoso',
                'keterangan' => 'Perbaikan Jalan',
                'status' => 'dikembalikan',
                'created_at' => now()->subDays(3)
            ],
            (object)[
                'id' => 4,
                'penerima' => 'Dewi Anggraeni',
                'keterangan' => 'Pengadaan Alat Tulis',
                'status' => 'menunggu',
                'created_at' => now()->subDays(4)
            ],
            (object)[
                'id' => 5,
                'penerima' => 'Rizky Pratama',
                'keterangan' => 'Proyek Instalasi Listrik',
                'status' => 'disetujui',
                'created_at' => now()->subDays(5)
            ]
        ]);
    }
}