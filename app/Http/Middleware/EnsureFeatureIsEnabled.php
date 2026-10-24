<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Symfony\Component\HttpFoundation\Response;

class EnsureFeatureIsEnabled
{
    public function handle(Request $request, Closure $next, string $featureKey): Response
    {
        if (Setting::get($featureKey, '1') == '0') {
            abort(403, 'Fitur ini sedang dinonaktifkan oleh administrator.');
        }

        return $next($request);
    }
}