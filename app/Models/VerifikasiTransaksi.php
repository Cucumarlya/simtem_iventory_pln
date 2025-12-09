<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VerifikasiTransaksi extends Model
{
    use HasFactory;

    // TABEL NAME SESUAI MIGRATION
    protected $table = 'verifikasi_transaksi';
    
    protected $fillable = [
        'transaksi_id',
        'diverifikasi_oleh',
        'tanggal_verifikasi',
        'status',
        'alasan_pengembalian',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->belongsTo(TransaksiMaterial::class, 'transaksi_id');
    }

    // Relasi ke user yang memverifikasi
    public function verifier()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
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
}