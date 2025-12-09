<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    
    protected $fillable = [
        'kode_material',
        'nama_material',
        'satuan',
        'stok_awal',
        'min_stok',
        'stok'
    ];
    
    protected $casts = [
        'stok_awal' => 'integer',
        'min_stok' => 'integer',
        'stok' => 'integer'
    ];
    
    // Relationship dengan detail transaksi
    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksiMaterial::class, 'material_id');
    }
    
    // Accessor untuk status material (tambahkan jika diperlukan)
    public function getStatusAttribute()
    {
        // Jika kolom status tidak ada, kita return 'aktif' sebagai default
        return 'aktif';
    }
    
    // Accessor untuk status text
    public function getStatusTextAttribute()
    {
        return 'Aktif';
    }
    
    // Accessor untuk status badge color
    public function getStatusBadgeAttribute()
    {
        return 'bg-green-100 text-green-800';
    }
    
    // Method untuk cek stok rendah
    public function getStokRendahAttribute()
    {
        return $this->stok <= $this->min_stok;
    }
    
    // Method untuk stok rendah badge
    public function getStokRendahBadgeAttribute()
    {
        if ($this->stok_rendah) {
            return 'bg-red-100 text-red-800';
        }
        return 'bg-green-100 text-green-800';
    }
    
    // Method untuk update stok
    public function updateStok($jumlah, $type = 'increment')
    {
        if ($type == 'increment') {
            $this->increment('stok', $jumlah);
        } else {
            $this->decrement('stok', $jumlah);
        }
        
        return $this;
    }
    
    // Scope untuk material aktif (dummy scope karena tidak ada kolom status)
    public function scopeAktif($query)
    {
        return $query; // Return semua karena tidak ada kolom status
    }
    
    // Scope untuk stok rendah
    public function scopeStokRendah($query)
    {
        return $query->whereRaw('stok <= min_stok');
    }
    
    // Scope untuk search
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('kode_material', 'like', "%{$search}%")
              ->orWhere('nama_material', 'like', "%{$search}%")
              ->orWhere('satuan', 'like', "%{$search}%");
        });
    }
}