<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipLog extends Model
{
    protected $fillable = [
        'student_id', 'industry_placement_id', 'teacher_id', 'date',
        'check_in_time', 'check_out_time', 'check_in_photo', 'check_in_lat', 'check_in_long',
        'check_out_photo', 'check_out_lat', 'check_out_long',
        'activity', 'score', 'teacher_notes',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function industry()
    {
        return $this->belongsTo(IndustryPlacement::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}