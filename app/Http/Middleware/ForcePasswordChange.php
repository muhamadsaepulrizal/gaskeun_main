<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->force_password_change) {
            // Biarkan user mengakses halaman ganti password atau logout
            if ($request->routeIs('auth.force-change-password', 'auth.force-change-password.post', 'logout')) {
                return $next($request);
            }
            
            return redirect()->route('auth.force-change-password')
                ->with('warning', 'Password Anda telah direset oleh Admin. Harap ganti password sekarang.');
        }

        return $next($request);
    }
}
