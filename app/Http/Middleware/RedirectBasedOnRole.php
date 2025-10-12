<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmışsa
        if (auth()->check()) {
            $user = auth()->user();
            
            // Rol bazlı yönlendirme
            if ($user->hasRole('super_admin')) {
                return redirect()->route('dashboard');
            } elseif ($user->hasRole(['admin', 'site_manager'])) {
                return redirect()->route('sites.index');
            } elseif ($user->hasRole('resident')) {
                return redirect()->route('resident.home');
            }
        }

        return $next($request);
    }
}
