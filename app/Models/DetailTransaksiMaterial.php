<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksiMaterial extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi_material';
    
    protected $fillable = [
        'transaksi_id',
        'material_id',
        'jumlah'
    ];
    
    // Relationship dengan transaksi
    public function transaksi()
    {
        return $this->belongsTo(TransaksiMaterial::class, 'transaksi_id');
    }
    
    // Relationship dengan material
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
    
    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        if ($this->material) {
            return $this->jumlah * ($this->material->harga ?? 0);
        }
        return 0;
    }
    
    // Method untuk mendapatkan informasi lengkap
    public function getInfoAttribute()
    {
        if ($this->material) {
            return $this->material->nama_material . ' (' . $this->jumlah . ' ' . $this->material->satuan . ')';
        }
        return 'Material tidak ditemukan';
    }
    
    // Method untuk mendapatkan data export
    public function getExportInfoAttribute()
    {
        if ($this->material) {
            return [
                'kode_material' => $this->material->kode_material,
                'nama_material' => $this->material->nama_material,
                'jumlah' => $this->jumlah,
                'satuan' => $this->material->satuan,
                'total' => $this->jumlah . ' ' . $this->material->satuan
            ];
        }
        return null;
    }
}