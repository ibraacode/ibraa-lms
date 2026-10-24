<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;
use App\Models\Setting;

class AcademicYear
{
    /**
     * Mendapatkan tahun ajaran yang sedang aktif di session.
     * Jika tidak ada di session (misal belum login), ambil dari Setting.
     */
    public static function current()
    {
        return Session::get('active_academic_year', Setting::get('active_academic_year', '2026/2027'));
    }

    /**
     * Set tahun ajaran aktif (untuk fitur ganti tahun ajaran).
     */
    public static function set($year)
    {
        Session::put('active_academic_year', $year);
    }
}