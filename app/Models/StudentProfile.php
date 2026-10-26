<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
        protected $fillable = [
        'user_id', 'classroom_id', 'status', 'graduation_year',
        'full_name', 'nickname', 'gender', 'birth_place', 'birth_date', 'address',
        'nik', 'kk_number', 'religion', 'child_order', 'siblings_count', 'living_with', 'special_needs',
        'father_name', 'father_status', 'father_job', 'father_education', 'father_phone',
        'mother_name', 'mother_status', 'mother_job', 'mother_education', 'mother_phone',
        'guardian_name', 'guardian_relation', 'guardian_job', 'guardian_phone', 'guardian_address',
  'registration_status', 'registration_completed_at',
        'is_verified', 'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'verified_at' => 'datetime',
            'is_verified' => 'boolean',
            'registration_completed_at' => 'datetime',
        ];
    }
  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
      public function isComplete()
    {
        // Daftar field wajib (bisa disesuaikan)
        $requiredFields = [
            'full_name', 'gender', 'birth_place', 'birth_date', 'address', 
            'father_name', 'mother_name', 'father_phone', 'nik', 'kk_number', 
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        return true;
    }
}