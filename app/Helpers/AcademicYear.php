<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\Setting;

class AcademicYear
{
    /**
     * Tahun ajaran yang sedang dipilih user (di dropdown).
     */
    public static function current()
    {
        return Session::get('active_academic_year', self::global());
    }

    /**
     * Tahun ajaran resmi di database (Tahun Ajaran Aktif).
     */
    public static function global()
    {
        return Setting::get('active_academic_year', '2026/2027');
    }

    /**
     * Cek apakah user sedang melihat tahun ajaran aktif (bukan arsip).
     */
    public static function isActive()
    {
        return self::current() === self::global();
    }
}