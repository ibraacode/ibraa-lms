<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
        protected $fillable = [
        'user_id', 'full_name', 'nickname', 'gender', 'birth_place', 'birth_date', 'address',
        'position', 'subject_specialty', 'employment_status', 'joined_at',
        'nik', 'religion', 'marital_status', 'last_education', 'university', 
        'major', 'graduation_year', 'npwp',
        'is_verified', 'verified_by', 'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'joined_at' => 'date',
            'verified_at' => 'datetime',
            'is_verified' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function isComplete()
    {
        $requiredFields = [
            'full_name', 'gender', 'birth_date', 'address', 
            'nik', 'last_education', 'employment_status'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }
        return true;
    }
}