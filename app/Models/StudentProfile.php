<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id', 'full_name', 'nickname', 'gender',
        'birth_place', 'birth_date', 'address',
        'class_name', 'major_code', 'academic_year',
        'parent_name', 'parent_phone',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
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
}