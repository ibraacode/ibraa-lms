<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function classrooms()
    {
        return $this->hasMany(Classroom::class, 'major_code', 'code');
    }
}