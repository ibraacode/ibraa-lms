<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CharacterGrade extends Model
{
    protected $fillable = [
        'student_id', 'academic_year', 'semester',
        'spiritual_grade', 'spiritual_notes',
        'social_grade', 'social_notes',
        'graded_by',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}