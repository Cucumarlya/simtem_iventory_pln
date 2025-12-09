<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMultipleRoles
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $allowedRoles = explode(',', $roles);

        if (!$user->role || !in_array($user->role->name, $allowedRoles)) {
            abort(403, 'Unauthorized - Role ' . $user->role->name . ' tidak diizinkan mengakses halaman ini');
        }

        return $next($request);
    }
}