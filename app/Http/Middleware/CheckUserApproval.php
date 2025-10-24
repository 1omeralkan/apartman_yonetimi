<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sadece giriş yapmış kullanıcıları kontrol et
        if (auth()->check()) {
            $user = auth()->user();
            
            // Admin kullanıcılar için kontrol yapma
            if ($user->hasRole('super_admin')) {
                return $next($request);
            }
            
            // Kullanıcı onaylanmamışsa
            if (!$user->isApproved()) {
                // Session'ı temizle ve kullanıcıyı çıkış yap
                $request->session()->flush();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Auth facade ile güvenli çıkış
                try {
                    Auth::logout();
                } catch (\Exception $e) {
                    // Eğer logout başarısız olursa, session'ı manuel temizle
                    $request->session()->forget('login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d');
                }
                
                // Onay bekleyen sayfaya yönlendir
                return redirect()->route('login')->with('warning', 
                    'Hesabınız henüz admin tarafından onaylanmamıştır. Lütfen onay bekleyiniz.'
                );
            }
        }

        return $next($request);
    }
}
