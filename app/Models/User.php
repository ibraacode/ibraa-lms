<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nis',
        'nip',
        'phone',
        'photo_path',
        'is_active',
        'last_login_at',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class);
    }

    public function isStudent(): bool
    {
        return $this->hasRole('siswa');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('guru');
    }
      public function schedulesAsTeacher()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
      public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }
      public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }
      public function characterGrades()
    {
        return $this->hasMany(CharacterGrade::class, 'student_id');
    }
      public function internshipPlacement()
    {
        return $this->hasOne(InternshipPlacement::class, 'student_id');
    }
    public function studentViolations()
    {
        return $this->hasMany(StudentViolation::class, 'student_id');
    }
}