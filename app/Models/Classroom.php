<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'grade_level', 'major_code', 'academic_year', 'homeroom_teacher_id'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
      public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }
      public function homeroomTeacher()
    {
        return $this->belongsTo(User::class, 'homeroom_teacher_id');
    }
}