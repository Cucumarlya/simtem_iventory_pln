<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ================== LOGIN (UNTUK SEMUA ROLE) ================== //
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // ================== LOGOUT (SEMUA ROLE) ================== //
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ================== HELPER METHOD ================== //
    private function redirectBasedOnRole()
    {
        $user = Auth::user();
        
        if (!$user->role) {
            Auth::logout();
            return redirect('/login')->withErrors([
                'email' => 'User tidak memiliki role yang valid.'
            ]);
        }

        return match ($user->role->name) {
            'admin' => redirect()->route('dashboard.admin'),
            'petugas' => redirect()->route('dashboard.petugas'),
            'teknisi' => redirect()->route('dashboard.petugas_yanbung'),
            default => redirect()->route('dashboard'),
        };
    }
}