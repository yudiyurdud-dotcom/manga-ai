<?php

// Kode ini diletakkan di app/Http/Middleware/IsAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pengecekan ganda: Pastikan dia sudah login, DAN role-nya adalah 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            // Jika dia admin asli, silakan masuk ke halaman yang dituju
            return $next($request);
        }

        // Jika dia tamu atau cuma user biasa yang iseng, lemparkan halaman Error 404.
        // Kita pakai 404 (Not Found) alih-alih 403 (Forbidden) untuk mengelabui peretas
        // agar mereka mengira URL admin tersebut salah atau tidak ada.
        abort(404);
    }
}