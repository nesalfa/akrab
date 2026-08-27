<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menolak akses ke rute manapun yang dipasangi middleware ini kalau
 * user yang login bukan admin (atau belum login sama sekali).
 * Daftarkan sebagai alias 'admin' — lihat catatan pendaftaran di README.
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Halaman ini khusus untuk admin. Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
