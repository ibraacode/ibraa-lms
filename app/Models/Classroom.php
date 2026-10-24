<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['name', 'grade_level', 'major_code', 'academic_year'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
      public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }
}