<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingCase extends Model
{
    protected $fillable = ['student_id', 'counselor_id', 'title', 'category', 'status'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function notes()
    {
        return $this->hasMany(CounselingNote::class);
    }
}