<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokMaterial extends Model
{
    protected $table = 'stok_material';

    protected $fillable = [
        'material_id',
        'tanggal',
        'masuk',
        'keluar',
        'transaksi_id',
        'stok_akhir'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function transaksi()
    {
        return $this->belongsTo(TransaksiMaterial::class, 'transaksi_id');
    }

    // Scope untuk mendapatkan stok per material per tanggal
    public function scopeForMaterial($query, $materialId)
    {
        return $query->where('material_id', $materialId);
    }

    // Scope untuk mendapatkan stok pada tanggal tertentu
    public function scopeUntilDate($query, $date)
    {
        return $query->where('tanggal', '<=', $date);
    }
}