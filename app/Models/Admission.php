<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $fillable = [
        'registration_number', 'full_name', 'nisn', 'gender', 'birth_place', 'birth_date',
        'previous_school', 'phone', 'email', 'address', 'parent_name', 'parent_phone',
        'chosen_major', 'status', 'notes', 'converted_user_id',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }
}