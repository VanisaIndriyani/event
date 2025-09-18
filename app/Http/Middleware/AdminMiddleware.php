<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            // Untuk AJAX request, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please login first.',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        // Update last activity untuk mencegah session timeout
        $request->session()->put('last_activity', time());

        if (auth()->user()->role !== 'admin') {
            // Untuk AJAX request, return JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Access denied. Admin privileges required.',
                    'redirect' => route('home')
                ], 403);
            }
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki izin admin.');
        }

        return $next($request);
    }
}
