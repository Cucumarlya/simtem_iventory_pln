<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixPathEncoding
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fix URL encoding issues
        $path = $request->getPathInfo();
        
        // Decode URL if it's double encoded
        if (preg_match('/%[0-9a-f]{2}/i', $path)) {
            $decodedPath = urldecode($path);
            // Ensure we don't decode too many times
            if ($decodedPath !== $path && !str_contains($decodedPath, '%')) {
                $request->server->set('REQUEST_URI', $decodedPath);
            }
        }
        
        return $next($request);
    }
}