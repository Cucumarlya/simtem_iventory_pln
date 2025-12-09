<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TransaksiMaterial extends Model
{
    use HasFactory;

    protected $table = 'transaksi_material';
    
    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'jenis',
        'nama_pihak_transaksi',
        'keperluan',
        'nomor_pelanggan',
        'foto_bukti',
        'foto_sr_sebelum',
        'foto_sr_sesudah',
        'dibuat_oleh',
        'status',
        'alasan_penolakan',
        'tanggal_verifikasi',
        'verifikator_id'
    ];
    
    protected $casts = [
        'tanggal' => 'date',
        'tanggal_verifikasi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Relationship dengan details
    public function details()
    {
        return $this->hasMany(DetailTransaksiMaterial::class, 'transaksi_id');
    }
    
    // Relationship dengan user yang membuat
    public function user()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
    
    // Relationship dengan verifikator
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
    
    // Accessor untuk status
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'disetujui' => 'Disetujui',
            'dikembalikan' => 'Dikembalikan',
            default => 'Menunggu'
        };
    }
    
    // Accessor untuk jenis
    public function getJenisTextAttribute()
    {
        return $this->jenis == 'penerimaan' ? 'Penerimaan' : 'Pengeluaran';
    }
    
    // Accessor untuk status badge color
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'disetujui' => 'bg-green-100 text-green-800',
            'dikembalikan' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800'
        };
    }
    
    // Accessor untuk keperluan badge color
    public function getKeperluanBadgeAttribute()
    {
        return match(strtolower($this->keperluan)) {
            'yanbung' => 'bg-blue-100 text-blue-800',
            'p2tl' => 'bg-green-100 text-green-800',
            'gangguan' => 'bg-red-100 text-red-800',
            'pln' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
    
    // Accessor untuk foto bukti URL
    public function getFotoBuktiUrlAttribute()
    {
        return $this->foto_bukti ? Storage::url($this->foto_bukti) : null;
    }
    
    // Accessor untuk foto SR sebelum URL
    public function getFotoSrSebelumUrlAttribute()
    {
        return $this->foto_sr_sebelum ? Storage::url($this->foto_sr_sebelum) : null;
    }
    
    // Accessor untuk foto SR sesudah URL
    public function getFotoSrSesudahUrlAttribute()
    {
        return $this->foto_sr_sesudah ? Storage::url($this->foto_sr_sesudah) : null;
    }
    
    // Scope untuk jenis
    public function scopePenerimaan($query)
    {
        return $query->where('jenis', 'penerimaan');
    }
    
    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }
    
    // Scope untuk status
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }
    
    public function scopeDikembalikan($query)
    {
        return $query->where('status', 'dikembalikan');
    }
    
    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }
    
    // Method untuk menghitung total material
    public function getTotalMaterialAttribute()
    {
        return $this->details->sum('jumlah');
    }
    
    // Method untuk mendapatkan daftar material
    public function getMaterialListAttribute()
    {
        return $this->details->map(function($detail) {
            return $detail->material->nama_material . ' (' . $detail->jumlah . ' ' . $detail->material->satuan . ')';
        })->implode(', ');
    }
    
    // Method untuk mengecek apakah bisa diedit
    public function getCanEditAttribute()
    {
        return $this->status == 'menunggu';
    }
    
    // Method untuk mengecek apakah bisa dihapus
    public function getCanDeleteAttribute()
    {
        return $this->status == 'menunggu';
    }
    
    // Method untuk mengecek stok availability (untuk pengeluaran)
    public function checkStockAvailability()
    {
        if ($this->jenis != 'pengeluaran') {
            return ['available' => true, 'messages' => []];
        }
        
        $messages = [];
        $available = true;
        
        foreach ($this->details as $detail) {
            $material = $detail->material;
            if ($material) {
                $stokTersedia = $material->stok;
                if ($stokTersedia < $detail->jumlah) {
                    $available = false;
                    $messages[] = "{$material->nama_material}: Stok tersedia {$stokTersedia}, diperlukan {$detail->jumlah}";
                }
            }
        }
        
        return ['available' => $available, 'messages' => $messages];
    }
    
    // Method untuk mendapatkan informasi export
    public function getExportDataAttribute()
    {
        return [
            'kode_transaksi' => $this->kode_transaksi,
            'tanggal' => $this->tanggal->format('d/m/Y'),
            'nama_pihak_transaksi' => $this->nama_pihak_transaksi,
            'keperluan' => $this->keperluan,
            'nomor_pelanggan' => $this->nomor_pelanggan,
            'material_list' => $this->material_list,
            'total_material' => $this->total_material,
            'status' => $this->status_text,
            'dibuat_oleh' => $this->user ? $this->user->name : '-',
            'verifikator' => $this->verifikator ? $this->verifikator->name : '-',
            'tanggal_verifikasi' => $this->tanggal_verifikasi ? $this->tanggal_verifikasi->format('d/m/Y H:i') : '-'
        ];
    }
}