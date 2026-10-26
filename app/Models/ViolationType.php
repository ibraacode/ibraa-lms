<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViolationType extends Model
{
    protected $fillable = ['name', 'description', 'points'];

    public function studentViolations()
    {
        return $this->hasMany(StudentViolation::class);
    }
}