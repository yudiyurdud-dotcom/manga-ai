<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackLastSeen
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Memperbarui waktu last_seen tanpa merusak kolom updated_at
            Auth::user()->timestamps = false;
            Auth::user()->forceFill(['last_seen' => now()])->save();
            Auth::user()->timestamps = true;
        }
        return $next($request);
    }
}