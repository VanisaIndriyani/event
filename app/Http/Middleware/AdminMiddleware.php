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
            // Cek apakah ada session yang masih valid
            if ($request->session()->has('last_activity')) {
                $lastActivity = $request->session()->get('last_activity');
                $sessionLifetime = config('session.lifetime') * 60; // convert to seconds
                
                // Jika session masih dalam batas waktu, jangan logout
                if (time() - $lastActivity < $sessionLifetime) {
                    // Coba restore user dari session
                    $userId = $request->session()->get('login_web_' . sha1('App\Models\User'));
                    if ($userId) {
                        auth()->loginUsingId($userId);
                        $request->session()->put('last_activity', time());
                    }
                }
            }
            
            // Jika masih tidak bisa login, redirect ke login
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
        }

        if (auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Anda tidak memiliki izin admin.');
        }

        return $next($request);
    }
}
