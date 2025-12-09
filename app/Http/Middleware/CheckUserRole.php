<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Debug logging
        \Log::info('CheckUserRole Middleware Check', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role ?? 'NULL',
            'required_roles' => $roles,
            'path' => $request->path()
        ]);

        // Cek jika user memiliki salah satu role yang diizinkan
        $hasRole = false;
        
        foreach ($roles as $role) {
            if ($this->hasRole($user, $role)) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            \Log::warning('Access denied by CheckUserRole middleware', [
                'user_id' => $user->id,
                'user_role' => $user->role ?? 'NULL',
                'required_roles' => $roles,
                'path' => $request->path()
            ]);
            
            abort(403, 'Akses tidak diizinkan. Hanya untuk role: ' . implode(', ', $roles) . '.');
        }

        return $next($request);
    }
    
    /**
     * Cek apakah user memiliki role tertentu
     */
    private function hasRole($user, $role)
    {
        // 1. Cek berdasarkan field langsung di tabel users
        if (isset($user->role) && $user->role === $role) {
            return true;
        }
        
        // 2. Cek berdasarkan relasi role (jika ada relasi role())
        if (method_exists($user, 'role') && $user->role) {
            if (is_object($user->role) && isset($user->role->name) && $user->role->name === $role) {
                return true;
            }
        }
        
        // 3. Cek jika menggunakan spatie/laravel-permission
        if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
            return true;
        }
        
        // 4. Fallback: cek berdasarkan nama role di model (jika ada)
        if (isset($user->role_name) && $user->role_name === $role) {
            return true;
        }
        
        return false;
    }
}