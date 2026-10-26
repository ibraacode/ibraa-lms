<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\AcademicYear;

class PreventPastYearModification
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. JIKA SEDANG DI HALAMAN PENGATURAN ATAU MANAJEMEN T.A, IZINKAN (Bypass)
        // Ini agar Admin bisa mengubah T.A Global meskipun sedang melihat tahun lama.
        if ($request->routeIs('admin.settings.*') || $request->routeIs('admin.academic-year.*')) {
            return $next($request);
        }

        // 2. JIKA BUKAN TAHUN AKTIF DAN MENCOBA UBAH DATA, BLOKIR
        if (!AcademicYear::isActive() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return back()->withErrors([
                'error' => 'Akses Ditolak: Tahun ajaran ' . AcademicYear::current() . ' sudah berakhir. Data hanya dapat dilihat (Read-Only).'
            ]);
        }

        return $next($request);
    }
}