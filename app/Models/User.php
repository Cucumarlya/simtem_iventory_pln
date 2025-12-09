<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Method untuk mengecek role
    public function isAdmin(): bool
    {
        return $this->role && $this->role->name === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role && $this->role->name === 'petugas';
    }

    public function isPetugasYanbung(): bool
    {
        return $this->role && $this->role->name === 'petugas_yanbung';
    }

    // Relasi dengan transaksi
    public function transaksiDibuat()
    {
        return $this->hasMany(TransaksiMaterial::class, 'dibuat_oleh');
    }

    public function verifikasiTransaksi()
    {
        return $this->hasMany(VerifikasiTransaksi::class, 'diverifikasi_oleh');
    }

    // Method untuk mengecek apakah user bisa akses
    public function canAccessTransaksiMaterial($transaksi)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        return $this->id === $transaksi->dibuat_oleh;
    }
}