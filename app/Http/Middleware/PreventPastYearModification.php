<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventPastYearModification
{
    public function handle(Request $request, Closure $next): Response
    {
        $currentYear = \App\Helpers\AcademicYear::current();
        $globalYear = \App\Models\Setting::get('active_academic_year', '2026/2027');

        // Jika yang dipilih bukan tahun aktif saat ini, dan user mencoba mengirim data (POST/PUT/DELETE)
        if ($currentYear !== $globalYear && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return back()->withErrors([
                'error' => 'Akses Ditolak: Tahun ajaran ' . $currentYear . ' sudah berakhir. Data hanya dapat dilihat (Read-Only).'
            ]);
        }

        return $next($request);
    }
}