<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;
use App\Models\Occupation;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ============ JURUSAN ============
        $majors = [
            ['code' => 'IPA', 'name' => 'Ilmu Pengetahuan Alam', 'description' => 'Khusus SMA'],
            ['code' => 'IPS', 'name' => 'Ilmu Pengetahuan Sosial', 'description' => 'Khusus SMA'],
            ['code' => 'BAHASA', 'name' => 'Bahasa & Budaya', 'description' => 'Khusus SMA'],
            ['code' => 'TKJ', 'name' => 'Teknik Komputer & Jaringan', 'description' => 'Khusus SMK'],
            ['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak', 'description' => 'Khusus SMK'],
            ['code' => 'TKRO', 'name' => 'Teknik Kendaraan Ringan Otomotif', 'description' => 'Khusus SMK'],
            ['code' => 'AKL', 'name' => 'Akuntansi & Keuangan Lembaga', 'description' => 'Khusus SMK'],
            ['code' => 'OTKP', 'name' => 'Otomatisasi & Tata Kelola Perkantoran', 'description' => 'Khusus SMK'],
        ];
        foreach ($majors as $m) {
            Major::firstOrCreate(['code' => $m['code']], $m);
        }

        // ============ PEKERJAAN ============
        $occupations = [
            'Tidak Bekerja', 'PNS', 'TNI/Polri', 'Karyawan Swasta', 'Wirausaha', 
            'Petani', 'Nelayan', 'Buruh', 'Guru/Dosen', 'Dokter/Perawat', 
            'Pedagang', 'Sopir', 'TKI', 'Pensiunan', 'Ibu Rumah Tangga', 'Lainnya'
        ];
        foreach ($occupations as $name) {
            Occupation::firstOrCreate(['name' => $name]);
        }
    }
}