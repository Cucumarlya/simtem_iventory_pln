<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\NewMaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StokMaterialController;
use App\Http\Controllers\NormalisasiController;
use App\Http\Controllers\RiwayatVerifikasiController;
use App\Http\Controllers\VerifikasiTransaksiController;
use App\Http\Controllers\TransaksiMaterialController;
use App\Http\Controllers\LaporanStokController;
use App\Http\Controllers\Petugas\DashboardPetugasController;
use App\Http\Controllers\Petugas\PenerimaanController;
use App\Http\Controllers\Petugas\PengeluaranController;
use App\Http\Controllers\Petugas\PenerimaanYanbungController;
use App\Http\Controllers\Petugas\PengeluaranYanbungController;
use App\Http\Middleware\CheckUserRole;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ================== HALAMAN LANDING PAGE ================== //
Route::get('/', function () {
    return view('landing-page');
})->name('landing-page');

// ================== AUTHENTICATION ================== //
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================== DASHBOARD BERDASARKAN ROLE ================== //
Route::middleware('auth')->group(function () {
    // Dashboard utama yang akan redirect berdasarkan role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard berdasarkan role
    Route::middleware([CheckUserRole::class . ':admin'])->group(function () {
        Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::get('/admin/dashboard', function () {
            return redirect()->route('dashboard.admin');
        })->name('admin.dashboard');
    });
    
    Route::middleware([CheckUserRole::class . ':petugas'])->group(function () {
        Route::get('/dashboard/petugas', [DashboardPetugasController::class, 'index'])->name('dashboard.petugas');
    });
    
    Route::middleware([CheckUserRole::class . ':petugas_yanbung'])->group(function () {
        Route::get('/dashboard/petugas_yanbung', [DashboardController::class, 'petugas_yanbung'])->name('dashboard.petugas_yanbung');
    });
});

// ================== ROUTES UNTUK ADMIN ================== //
Route::middleware(['auth', CheckUserRole::class . ':admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
    
    // ================== TRANSAKSI MATERIAL ================== //
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        // Index dan CRUD - ROUTE UTAMA
        Route::get('/', [TransaksiMaterialController::class, 'index'])->name('index');
        
        // Route untuk form create berdasarkan jenis
        Route::get('/create/{jenis}', [TransaksiMaterialController::class, 'create'])
            ->where('jenis', 'penerimaan|pengeluaran')
            ->name('create');
        
        // Alias untuk route create (tanpa parameter) - redirect ke index
        Route::get('/create', function() {
            return redirect()->route('admin.transaksi.index');
        });
        
        Route::post('/', [TransaksiMaterialController::class, 'store'])->name('store');
        Route::get('/{transaksi}', [TransaksiMaterialController::class, 'show'])->name('show');
        Route::get('/{transaksi}/edit', [TransaksiMaterialController::class, 'edit'])->name('edit');
        Route::put('/{transaksi}', [TransaksiMaterialController::class, 'update'])->name('update');
        Route::delete('/{transaksi}', [TransaksiMaterialController::class, 'destroy'])->name('destroy');
        
        // Export & Print Routes
        Route::get('/export/excel', [TransaksiMaterialController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [TransaksiMaterialController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/csv', [TransaksiMaterialController::class, 'exportCsv'])->name('export.csv');
        Route::get('/print', [TransaksiMaterialController::class, 'print'])->name('print');
        Route::get('/{id}/print', [TransaksiMaterialController::class, 'printSingle'])->name('print.single');
        
        // Verification Routes
        Route::post('/{id}/setujui', [TransaksiMaterialController::class, 'setujui'])->name('setujui');
        Route::post('/{id}/kembalikan', [TransaksiMaterialController::class, 'kembalikan'])->name('kembalikan');
        
        // API untuk AJAX
        Route::get('/data/json', [TransaksiMaterialController::class, 'getDataJson'])->name('data.json');
        
        // By jenis
        Route::get('/jenis/{jenis}', [TransaksiMaterialController::class, 'byJenis'])
            ->where('jenis', 'penerimaan|pengeluaran')
            ->name('byJenis');
    });

    // ================== MASTER MATERIAL ================== //
    Route::prefix('master')->name('master.')->group(function () {
        Route::prefix('materials')->name('material.')->group(function () {
            Route::get('/', [MaterialController::class, 'index'])->name('index');
            Route::get('/create', [MaterialController::class, 'create'])->name('create');
            Route::post('/', [MaterialController::class, 'store'])->name('store');
            Route::get('/{id}', [MaterialController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
            Route::get('/search', [MaterialController::class, 'search'])->name('search');
            Route::get('/export/excel', [MaterialController::class, 'exportExcel'])->name('export.excel');
            
            // AJAX routes
            Route::get('/ajax/search', function(Request $request) {
                try {
                    $search = $request->get('search', '');
                    $materials = \App\Models\Material::where(function($query) use ($search) {
                            $query->where('nama_material', 'like', "%{$search}%")
                                  ->orWhere('kode_material', 'like', "%{$search}%");
                        })
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);
                    
                    return view('admin.master.partials.material-table', compact('materials'))->render();
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            })->name('ajax.search');
        });
    });
    
    // ================== ALIAS UNTUK COMPATIBILITY ================== //
    Route::prefix('material')->name('material.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.master.material.index');
        })->name('index');
        Route::get('/create', function () {
            return redirect()->route('admin.master.material.create');
        })->name('create');
        Route::post('/', [MaterialController::class, 'store'])->name('store');
        Route::get('/{id}', function ($id) {
            return redirect()->route('admin.master.material.show', $id);
        })->name('show');
        Route::get('/{id}/edit', function ($id) {
            return redirect()->route('admin.master.material.edit', $id);
        })->name('edit');
        Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
        Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
        Route::get('/search', function(Request $request) {
            return redirect()->route('admin.master.material.search', $request->all());
        })->name('search');
    });

    // NEW MATERIAL SYSTEM
    Route::prefix('new-material')->name('new-material.')->group(function () {
        Route::get('/', [NewMaterialController::class, 'index'])->name('index');
        Route::get('/create', [NewMaterialController::class, 'create'])->name('create');
        Route::post('/', [NewMaterialController::class, 'store'])->name('store');
        Route::get('/{material}', [NewMaterialController::class, 'show'])->name('show');
        Route::get('/{material}/edit', [NewMaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}', [NewMaterialController::class, 'update'])->name('update');
        Route::delete('/{material}', [NewMaterialController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [NewMaterialController::class, 'exportExcel'])->name('export.excel');
    });

    // ================== VERIFIKASI DAN RIWAYAT ================== //
    Route::prefix('verifikasi')->name('verifikasi.')->group(function () {
        Route::get('/', [VerifikasiTransaksiController::class, 'index'])->name('index');
        Route::get('/data', [VerifikasiTransaksiController::class, 'getData'])->name('data');
        Route::get('/{id}/detail', [VerifikasiTransaksiController::class, 'getDetail'])->name('detail');
        Route::get('/detail/{type}/{id}', [VerifikasiTransaksiController::class, 'detailPage'])->name('detail.page');
        Route::post('/{id}/verify', [VerifikasiTransaksiController::class, 'verify'])->name('verify');
        Route::get('/export/excel', [VerifikasiTransaksiController::class, 'exportExcel'])->name('export.excel');
    });

    Route::prefix('riwayat-verifikasi')->name('riwayat-verifikasi.')->group(function () {
        Route::get('/', [RiwayatVerifikasiController::class, 'index'])->name('index');
        Route::get('/data', [RiwayatVerifikasiController::class, 'getData'])->name('data');
        Route::get('/export/excel', [RiwayatVerifikasiController::class, 'exportExcel'])->name('export.excel');
    });

    // ================== STOK DAN REKAP ================== //
    Route::prefix('rekap-stok')->name('rekap-stok.')->group(function () {
        Route::get('/', [LaporanStokController::class, 'rekap'])->name('index');
        Route::get('/detail/{materialId}', [LaporanStokController::class, 'detail'])->name('detail');
        Route::get('/export/pdf', [LaporanStokController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [LaporanStokController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/csv', [LaporanStokController::class, 'exportCsv'])->name('export.csv');
    });

    Route::prefix('stok-material')->name('stok-material.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.rekap-stok.index');
        })->name('index');
        Route::get('/create', function () {
            return view('admin.rekap_stok.create');
        })->name('create');
        Route::post('/', [LaporanStokController::class, 'store'])->name('store');
        Route::get('/{id}/edit', function ($id) {
            return view('admin.rekap_stok.edit', compact('id'));
        })->name('edit');
        Route::put('/{id}', [LaporanStokController::class, 'update'])->name('update');
        Route::delete('/{id}', [LaporanStokController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [LaporanStokController::class, 'exportExcel'])->name('export.excel');
    });

    // ================== KELOLA USER ================== //
    Route::prefix('kelola-user')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        
        Route::get('/user-view', function() {
            return view('admin.kelola_user.index');
        })->name('user-view');
    });

    // ================== LAINNYA ================== //
    Route::prefix('normalisasi')->name('normalisasi.')->group(function () {
        Route::get('/', [NormalisasiController::class, 'index'])->name('index');
        Route::get('/create', [NormalisasiController::class, 'create'])->name('create');
        Route::post('/', [NormalisasiController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [NormalisasiController::class, 'edit'])->name('edit');
        Route::put('/{id}', [NormalisasiController::class, 'update'])->name('update');
        Route::delete('/{id}', [NormalisasiController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [NormalisasiController::class, 'exportExcel'])->name('export.excel');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', function () {
            return view('admin.laporan.index');
        })->name('index');
        Route::get('/transaksi', function () {
            return view('admin.laporan.transaksi');
        })->name('transaksi');
        Route::get('/stok', function () {
            return view('admin.laporan.stok');
        })->name('stok');
        Route::get('/kinerja', function () {
            return view('admin.laporan.kinerja');
        })->name('kinerja');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');
        Route::get('/general', function () {
            return view('admin.settings.general');
        })->name('general');
        Route::get('/notifications', function () {
            return view('admin.settings.notifications');
        })->name('notifications');
    });
});

// ================== ROUTES UNTUK PETUGAS ================== //
Route::middleware(['auth', CheckUserRole::class . ':petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [DashboardPetugasController::class, 'index'])->name('dashboard');
    
    Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
        Route::get('/', [PenerimaanController::class, 'index'])->name('index');
        Route::get('/create', [PenerimaanController::class, 'create'])->name('create');
        Route::post('/', [PenerimaanController::class, 'store'])->name('store');
        Route::get('/{id}', [PenerimaanController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PenerimaanController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenerimaanController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenerimaanController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PenerimaanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}/print', [PenerimaanController::class, 'print'])->name('print');
    });
    
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranController::class, 'store'])->name('store');
        Route::get('/{id}', [PengeluaranController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PengeluaranController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengeluaranController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengeluaranController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PengeluaranController::class, 'exportExcel'])->name('export.excel');
        Route::get('/{id}/print', [PengeluaranController::class, 'print'])->name('print');
    });
});

// ================== ROUTES UNTUK PETUGAS YANBUNG ================== //
Route::middleware(['auth', CheckUserRole::class . ':petugas_yanbung'])->prefix('petugas-yanbung')->name('petugas_yanbung.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas_yanbung.dashboard.index');
    })->name('dashboard');
    
    Route::prefix('penerimaan')->name('penerimaan.')->group(function () {
        Route::get('/', [PenerimaanYanbungController::class, 'index'])->name('index');
        Route::get('/create', [PenerimaanYanbungController::class, 'create'])->name('create');
        Route::post('/', [PenerimaanYanbungController::class, 'store'])->name('store');
        Route::get('/{id}', [PenerimaanYanbungController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PenerimaanYanbungController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PenerimaanYanbungController::class, 'update'])->name('update');
        Route::delete('/{id}', [PenerimaanYanbungController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PenerimaanYanbungController::class, 'exportExcel'])->name('export.excel');
    });
    
    Route::prefix('pengeluaran')->name('pengeluaran.')->group(function () {
        Route::get('/', [PengeluaranYanbungController::class, 'index'])->name('index');
        Route::get('/create', [PengeluaranYanbungController::class, 'create'])->name('create');
        Route::post('/', [PengeluaranYanbungController::class, 'store'])->name('store');
        Route::get('/{id}', [PengeluaranYanbungController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PengeluaranYanbungController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PengeluaranYanbungController::class, 'update'])->name('update');
        Route::delete('/{id}', [PengeluaranYanbungController::class, 'destroy'])->name('destroy');
        Route::get('/export/excel', [PengeluaranYanbungController::class, 'exportExcel'])->name('export.excel');
    });
});

// ================== SHARED ROUTES ================== //
Route::middleware('auth')->group(function () {
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', function () {
            return view('profile.index');
        })->name('index');
        Route::get('/edit', function () {
            return view('profile.edit');
        })->name('edit');
        Route::get('/security', function () {
            return view('profile.security');
        })->name('security');
        Route::get('/notifications', function () {
            return view('profile.notifications');
        })->name('notifications');
    });
    
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', function () {
            return view('notifications.index');
        })->name('index');
        Route::get('/unread', function () {
            return view('notifications.unread');
        })->name('unread');
        Route::get('/all', function () {
            return view('notifications.all');
        })->name('all');
    });
    
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', function () {
            return view('help.index');
        })->name('index');
        Route::get('/faq', function () {
            return view('help.faq');
        })->name('faq');
        Route::get('/contact', function () {
            return view('help.contact');
        })->name('contact');
    });
});

// ================== API ROUTES ================== //
Route::middleware(['auth'])->prefix('api')->name('api.')->group(function () {
    // Transaksi routes
    Route::get('/transaksi', [TransaksiMaterialController::class, 'apiIndex']);
    Route::get('/transaksi/{id}', [TransaksiMaterialController::class, 'apiShow']);
    
    // Export routes
    Route::get('/export/transaksi/excel', [TransaksiMaterialController::class, 'exportExcel']);
    Route::get('/export/transaksi/pdf', [TransaksiMaterialController::class, 'exportPdf']);
    Route::get('/export/transaksi/csv', [TransaksiMaterialController::class, 'exportCsv']);
});

// ================== ROUTE ALIAS ================== //
// Alias untuk admin transaksi
Route::middleware(['auth', CheckUserRole::class . ':admin'])->group(function () {
    // Transaksi Aliases
    Route::get('/transaksi', function () {
        return redirect()->route('admin.transaksi.index');
    })->name('transaksi.index');
    
    Route::get('/transaksi/create/penerimaan', function () {
        return redirect()->route('admin.transaksi.create', ['jenis' => 'penerimaan']);
    })->name('transaksi.create.penerimaan');
    
    Route::get('/transaksi/create/pengeluaran', function () {
        return redirect()->route('admin.transaksi.create', ['jenis' => 'pengeluaran']);
    })->name('transaksi.create.pengeluaran');
    
    // Other Aliases
    Route::get('/stok-material', function () {
        return redirect()->route('admin.rekap-stok.index');
    })->name('stok-material.index');
    Route::get('/material', function () {
        return redirect()->route('admin.master.material.index');
    })->name('material.index');
    Route::get('/master/materials', function () {
        return redirect()->route('admin.master.material.index');
    })->name('master.material.index');
    
    Route::get('/users', function () {
        return redirect()->route('admin.users.index');
    })->name('users.index');
    Route::get('/users/create', function () {
        return redirect()->route('admin.users.create');
    })->name('users.create');
    Route::get('/users/{user}/edit', function ($user) {
        return redirect()->route('admin.users.edit', $user);
    })->name('users.edit');
    
    Route::get('/kelola-user', function () {
        return redirect()->route('admin.users.index');
    })->name('kelola-user.index');
    Route::get('/kelola-user/search', function (Request $request) {
        return redirect()->route('admin.users.search', $request->all());
    })->name('kelola-user.search');
    
    Route::get('/new-material', function () {
        return redirect()->route('admin.new-material.index');
    })->name('new-material.index');
    Route::get('/transaksi-material', function () {
        return redirect()->route('admin.transaksi.index');
    })->name('transaksi-material.index');
    Route::get('/verifikasi', function () {
        return redirect()->route('admin.verifikasi.index');
    })->name('verifikasi.index');
    Route::get('/riwayat-verifikasi', function () {
        return redirect()->route('admin.riwayat-verifikasi.index');
    })->name('riwayat-verifikasi.index');
    Route::get('/rekap-stok', function () {
        return redirect()->route('admin.rekap-stok.index');
    })->name('rekap-stok.index');
    Route::get('/normalisasi', function () {
        return redirect()->route('admin.normalisasi.index');
    })->name('normalisasi.index');
    Route::get('/transaksi/riwayat', function () {
        return redirect()->route('admin.riwayat-verifikasi.index');
    })->name('transaksi.riwayat');
    Route::get('/transaksi/penerimaan', function () {
        return redirect()->route('admin.transaksi.index', ['tab' => 'penerimaan']);
    })->name('transaksi.penerimaan');
    Route::get('/transaksi/pengeluaran', function () {
        return redirect()->route('admin.transaksi.index', ['tab' => 'pengeluaran']);
    })->name('transaksi.pengeluaran');
    
    Route::get('/transaksi/rhwayat', function () {
        return redirect()->route('admin.riwayat-verifikasi.index');
    })->name('transaksi.rhwayat');
    
    // Export aliases
    Route::get('/transaksi/export', function () {
        return redirect()->route('admin.transaksi.export.excel');
    })->name('transaksiexport');
    Route::get('/transaksi/export/excel', function () {
        return redirect()->route('admin.transaksi.export.excel');
    })->name('transaksi.export.excel');
    Route::get('/transaksi/export/pdf', function () {
        return redirect()->route('admin.transaksi.export.pdf');
    })->name('transaksi.export.pdf');
    Route::get('/transaksi/export/csv', function () {
        return redirect()->route('admin.transaksi.export.csv');
    })->name('transaksi.export.csv');
    Route::get('/transaksi/print', function () {
        return redirect()->route('admin.transaksi.print');
    })->name('transaksi.print');
    Route::get('/transaksi/{id}/print', function ($id) {
        return redirect()->route('admin.transaksi.print.single', $id);
    })->name('transaksi.print.single');
});

// ================== Petugas route aliases ================== //
Route::middleware(['auth', CheckUserRole::class . ':petugas'])->group(function () {
    Route::get('/transaksi/penerimaan/create', function () {
        return redirect()->route('petugas.penerimaan.create');
    })->name('transaksi.penerimaan.create');
    Route::get('/transaksi', function () {
        return redirect()->route('petugas.penerimaan.index');
    })->name('transaksi.index');
    Route::get('/transaksi/riwayat', function () {
        return redirect()->route('petugas.penerimaan.index');
    })->name('transaksi.riwayat');
    Route::get('/transaksi/penerimaan', function () {
        return redirect()->route('petugas.penerimaan.index');
    })->name('transaksi.penerimaan');
    Route::get('/transaksi/pengeluaran', function () {
        return redirect()->route('petugas.pengeluaran.index');
    })->name('transaksi.pengeluaran');
    Route::get('/transaksi/rhwayat', function () {
        return redirect()->route('petugas.penerimaan.index');
    })->name('transaksi.rhwayat');
});

// ================== Petugas Yanbung route aliases ================== //
Route::middleware(['auth', CheckUserRole::class . ':petugas_yanbung'])->group(function () {
    Route::get('/transaksi/penerimaan/create', function () {
        return redirect()->route('petugas_yanbung.penerimaan.create');
    })->name('transaksi.penerimaan.create');
    Route::get('/transaksi', function () {
        return redirect()->route('petugas_yanbung.penerimaan.index');
    })->name('transaksi.index');
    Route::get('/transaksi/riwayat', function () {
        return redirect()->route('petugas_yanbung.penerimaan.index');
    })->name('transaksi.riwayat');
    Route::get('/transaksi/penerimaan', function () {
        return redirect()->route('petugas_yanbung.penerimaan.index');
    })->name('transaksi.penerimaan');
    Route::get('/transaksi/pengeluaran', function () {
        return redirect()->route('petugas_yanbung.pengeluaran.index');
    })->name('transaksi.pengeluaran');
    Route::get('/transaksi/rhwayat', function () {
        return redirect()->route('petugas_yanbung.penerimaan.index');
    })->name('transaksi.rhwayat');
});

// ================== DEBUG & TEST ROUTES ================== //
Route::get('/debug/material/{id}', function($id) {
    $material = \App\Models\Material::find($id);
    $hasTransactions = \App\Models\MaterialTransaksi::where('material_id', $id)->exists();
    
    $columns = \Illuminate\Support\Facades\Schema::getColumnListing('materials');
    
    return response()->json([
        'material' => $material,
        'has_transactions' => $hasTransactions,
        'transaction_count' => \App\Models\MaterialTransaksi::where('material_id', $id)->count(),
        'columns_in_table' => $columns,
        'has_stok_awal_column' => in_array('stok_awal', $columns),
        'has_min_stok_column' => in_array('min_stok', $columns),
        'has_stok_column' => in_array('stok', $columns),
        'has_status_column' => in_array('status', $columns)
    ]);
})->name('debug.material');

Route::get('/test-simple', function() {
    return "Hello World! Sistem berjalan.";
})->name('test.simple');

Route::get('/test-material-list', function() {
    $materials = \App\Models\Material::all();
    return response()->json([
        'success' => true,
        'count' => $materials->count(),
        'materials' => $materials->map(function($material) {
            return [
                'id' => $material->id,
                'kode_material' => $material->kode_material,
                'nama_material' => $material->nama_material,
                'satuan' => $material->satuan,
                'stok_awal' => $material->stok_awal,
                'min_stok' => $material->min_stok
            ];
        })
    ]);
})->name('test.material.list');

Route::get('/test-transaksi-details', function() {
    $transaksi = \App\Models\TransaksiMaterial::with(['details.material'])->first();
    
    if (!$transaksi) {
        return response()->json(['error' => 'Tidak ada transaksi'], 404);
    }
    
    return response()->json([
        'success' => true,
        'transaksi' => $transaksi,
        'details_count' => $transaksi->details->count(),
        'details' => $transaksi->details->map(function($detail) {
            return [
                'id' => $detail->id,
                'material_id' => $detail->material_id,
                'material_name' => $detail->material ? $detail->material->nama_material : 'N/A',
                'jumlah' => $detail->jumlah,
                'satuan' => $detail->material ? $detail->material->satuan : 'N/A'
            ];
        })
    ]);
})->name('test.transaksi.details');

Route::get('/test-material-status-error', function() {
    try {
        $materials = \App\Models\Material::where('status', 'aktif')->get();
        return response()->json(['success' => true, 'materials' => $materials]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'solution' => 'Hapus where(\'status\', \'aktif\') dari query'
        ]);
    }
})->name('test.material.status.error');

Route::get('/test-user-route', function() {
    return response()->json([
        'success' => true,
        'routes' => [
            'admin.users.index' => route('admin.users.index'),
            'admin.users.search' => route('admin.users.search'),
            'kelola-user.index' => route('kelola-user.index'),
            'v4e0b.user' => route('v4e0b.user', [], false),
        ],
        'message' => 'Test route untuk debugging user management'
    ]);
})->name('test.user.route');

// ================== HEALTH CHECK ================== //
Route::get('/health', function() {
    $databaseConnected = false;
    $databaseName = 'N/A';
    
    try {
        \DB::connection()->getPdo();
        $databaseConnected = true;
        $databaseName = \DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        $databaseConnected = false;
    }
    
    return response()->json([
        'status' => 'OK',
        'timestamp' => now()->toDateTimeString(),
        'service' => 'SINVOSAR Material Management System',
        'version' => '1.0.0',
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
        'environment' => app()->environment(),
        'database' => [
            'connected' => $databaseConnected,
            'name' => $databaseName
        ],
        'system' => [
            'memory_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB',
            'max_memory' => ini_get('memory_limit'),
            'execution_time' => round(microtime(true) - LARAVEL_START, 3) . ' seconds'
        ],
        'counts' => [
            'materials' => \App\Models\Material::count(),
            'transactions' => \App\Models\TransaksiMaterial::count(),
            'users' => \App\Models\User::count(),
        ]
    ]);
})->name('health.check');

Route::get('/maintenance', function() {
    return view('maintenance');
})->name('maintenance');

// ================== FALLBACK ROUTE ================== //
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json([
            'error' => 'Route not found',
            'message' => 'The requested URL was not found on this server.',
            'timestamp' => now()->toDateTimeString(),
            'path' => request()->path(),
            'method' => request()->method()
        ], 404);
    }
    
    return response()->view('errors.404', [
        'title' => 'Halaman Tidak Ditemukan',
        'message' => 'Halaman yang Anda cari tidak ditemukan.',
        'code' => 404
    ], 404);
});