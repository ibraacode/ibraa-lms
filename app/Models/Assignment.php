<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['schedule_id', 'title', 'description', 'due_date', 'max_score'];

    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}