<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyAttendanceToken extends Model
{
    protected $fillable = ['token', 'type', 'is_active'];
}