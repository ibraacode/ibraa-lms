<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('school_name', 'SMA IbraLMS');
        Setting::set('school_address', 'Jl. Pendidikan No. 1, Jakarta');
        Setting::set('school_phone', '021-1234567');
        Setting::set('school_logo', null);
        Setting::set('active_academic_year', '2026/2027');
        Setting::set('school_type', 'negeri');
      Setting::set('feature_qr_attendance', '1'); // 1 = aktif, 0 = nonaktif
        Setting::set('feature_finance', '1');
              Setting::set('feature_ppdb', '1');
    }
}