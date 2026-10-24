<?php

namespace App\Imports;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithChunkReading
{
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function model(array $row): ?Model
    {
        // Cek duplikasi
        if ($this->role === 'siswa') {
            if (empty($row['nis']) || User::where('nis', $row['nis'])->exists()) {
                return null;
            }
            $email = $row['nis'] . '@' . config('school.student_domain');
        } else {
            if (empty($row['nip']) || User::where('nip', $row['nip'])->exists()) {
                return null;
            }
            $email = $row['nip'] . '@' . config('school.teacher_domain');
        }

        $user = User::create([
            'name' => $row['nama'] ?? 'Tanpa Nama',
            'email' => $email,
            'nis' => $this->role === 'siswa' ? $row['nis'] : null,
            'nip' => $this->role === 'guru' ? $row['nip'] : null,
            'password' => Hash::make('IbraLMS123!'),
            'is_active' => true,
            'must_change_password' => '1',
        ]);

        $user->assignRole($this->role);

        if ($this->role === 'siswa') {
            StudentProfile::create([
                'user_id' => $user->id,
                'full_name' => $row['nama'] ?? 'Tanpa Nama',
                'gender' => $row['jenis_kelamin'] ?? 'L',
                'class_name' => $row['kelas'] ?? null,
            ]);
        } else {
            TeacherProfile::create([
                'user_id' => $user->id,
                'full_name' => $row['nama'] ?? 'Tanpa Nama',
                'gender' => $row['jenis_kelamin'] ?? 'L',
            ]);
        }

        return $user;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}