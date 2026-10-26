<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // Identitas Sekolah
        Setting::set('school_name', 'SMA IbraLMS');
        Setting::set('school_address', 'Jl. Pendidikan No. 1, Jakarta');
        Setting::set('school_phone', '021-1234567');
        Setting::set('school_logo', null);
        Setting::set('school_type', 'negeri'); // negeri / swasta
        
        // Tahun Ajaran
        Setting::set('active_academic_year', '2026/2027');
        
        // Feature Toggles (Saklar Fitur)
        Setting::set('feature_qr_attendance', '1'); // 1 = aktif, 0 = nonaktif
        Setting::set('feature_finance', '1');
        Setting::set('feature_ppdb', '1');
        
        // ================= PENGATURAN PPDB =================
        Setting::set('ppdb_mode', 'swasta'); // 'swasta' (buka pendaftaran) atau 'negeri' (hanya daftar ulang)
        Setting::set('ppdb_quota', '350'); // Kuota maksimal siswa yang diterima
        Setting::set('ppdb_fee', '0'); // Biaya pendaftaran (0 = gratis)
        Setting::set('ppdb_registration_start', '2026-05-01'); // Tanggal buka
        Setting::set('ppdb_registration_end', '2026-07-15'); // Tanggal tutup
        Setting::set('ppdb_announcement_date', '2026-07-20'); // Tanggal pengumuman
    }
}