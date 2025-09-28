<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Kullanıcı giriş yapmamışsa login sayfasına yönlendir
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Kullanıcının belirtilen rollerden birine sahip olup olmadığını kontrol et
        if (!auth()->user()->hasAnyRole($roles)) {
            // Yetkisiz erişim - 403 hatası döndür
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
}
