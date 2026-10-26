<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classroom;
use App\Models\User;

class ClassroomSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kelas untuk tahun ajaran 2027/2028
        Classroom::firstOrCreate([
            'name' => 'XI IPA 1',
            'academic_year' => '2027/2028',
        ], [
            'grade_level' => 'XI',
            'major_code' => 'IPA',
        ]);
    }
}