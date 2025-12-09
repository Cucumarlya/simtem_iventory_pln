<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\TransaksiMaterial;
use App\Models\Material;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate untuk admin
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk petugas
        Gate::define('petugas', function (User $user) {
            return $user->role === 'petugas';
        });

        // Gate untuk petugas_yanbung
        Gate::define('petugas_yanbung', function (User $user) {
            return $user->role === 'petugas_yanbung';
        });

        // Gate untuk melihat semua transaksi (admin saja)
        Gate::define('view-all-transactions', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk mengedit transaksi
        Gate::define('edit-transaction', function (User $user, TransaksiMaterial $transaction) {
            // Admin bisa edit semua transaksi
            if ($user->role === 'admin') {
                return true;
            }
            
            // Petugas hanya bisa edit transaksi yang mereka buat
            return $user->role === 'petugas' && $transaction->dibuat_oleh === $user->id;
        });

        // Gate untuk menghapus transaksi
        Gate::define('delete-transaction', function (User $user, TransaksiMaterial $transaction) {
            // Admin bisa hapus semua transaksi
            if ($user->role === 'admin') {
                return true;
            }
            
            // Petugas hanya bisa hapus transaksi yang mereka buat dan status menunggu
            return $user->role === 'petugas' && 
                   $transaction->dibuat_oleh === $user->id && 
                   $transaction->status === 'menunggu';
        });

        // Gate untuk mengelola material
        Gate::define('manage-materials', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk mengelola users
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk verifikasi transaksi
        Gate::define('verify-transaction', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk melihat dashboard admin
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->role === 'admin';
        });

        // Gate untuk melihat dashboard petugas
        Gate::define('view-petugas-dashboard', function (User $user) {
            return $user->role === 'petugas' || $user->role === 'petugas_yanbung';
        });
    }
}