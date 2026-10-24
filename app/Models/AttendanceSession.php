<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['schedule_id', 'date', 'opened_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function openedBy()
    {
        return $this->belongsTo(User::class, 'opened_by');
    }
}