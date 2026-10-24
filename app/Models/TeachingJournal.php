<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingJournal extends Model
{
    protected $fillable = ['schedule_id', 'teacher_id', 'date', 'material_covered', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}