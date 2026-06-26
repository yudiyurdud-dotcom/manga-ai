<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class CheckMaintenanceMode
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. PINTU DARURAT: Izinkan akses ke rute login, logout, dan proses autentikasi lainnya
        // Meskipun maintenance menyala, form login tidak akan pernah diblokir.
        if ($request->is('login') || $request->is('logout') || $request->is('register')) {
            return $next($request);
        }

        $setting = Setting::first();

        // 2. Jika mode maintenance menyala
        if ($setting && $setting->maintenance_mode) {
            
            // Izinkan lolos jika user yang login adalah admin
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // Jika bukan admin (tamu/user biasa), lempar halaman error 503
            abort(503, 'Website ' . ($setting->site_name ?? 'ini') . ' sedang dalam masa perbaikan (Maintenance). Silakan kembali beberapa saat lagi.');
        }

        return $next($request);
    }
}