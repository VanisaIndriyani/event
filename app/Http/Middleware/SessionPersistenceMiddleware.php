<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionPersistenceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user sudah login, perpanjang session lifetime
        if (Auth::check()) {
            // Set session lifetime menjadi lebih lama (24 jam = 1440 menit)
            config(['session.lifetime' => 1440]);
            
            // Regenerate session ID untuk keamanan tanpa logout
            if (!$request->session()->has('session_regenerated_at') || 
                time() - $request->session()->get('session_regenerated_at', 0) > 3600) {
                $request->session()->regenerate(false); // false = jangan destroy data
                $request->session()->put('session_regenerated_at', time());
            }
            
            // Update last activity timestamp
            $request->session()->put('last_activity', time());
            
            // Pastikan remember token tetap valid
            if ($request->session()->has('login.remember') && 
                $request->session()->get('login.remember') === true) {
                // Extend remember token jika ada
                $user = Auth::user();
                if ($user && !$user->remember_token) {
                    $user->setRememberToken(\Illuminate\Support\Str::random(60));
                    $user->save();
                }
            }
        }
        
        $response = $next($request);
        
        // Set cookie dengan lifetime yang lebih panjang untuk session persistence
        if (Auth::check()) {
            $cookie = cookie(
                'laravel_session_persistent',
                $request->session()->getId(),
                1440, // 24 jam
                '/',
                null,
                false,
                true // httpOnly
            );
            
            // Check response type and handle accordingly
            if ($response instanceof \Symfony\Component\HttpFoundation\StreamedResponse ||
                $response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
                // For StreamedResponse and BinaryFileResponse, set cookie using headers
                $response->headers->setCookie($cookie);
            } elseif (method_exists($response, 'withCookie')) {
                // For regular responses that support withCookie method
                $response->withCookie($cookie);
            } else {
                // Fallback: set cookie using headers for any other response type
                $response->headers->setCookie($cookie);
            }
        }
        
        return $response;
    }
}