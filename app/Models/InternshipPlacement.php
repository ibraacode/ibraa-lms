<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipPlacement extends Model
{
    protected $fillable = [
        'student_id', 'industry_placement_id', 'teacher_id',
        'start_date', 'end_date', 'final_score', 'final_notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
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