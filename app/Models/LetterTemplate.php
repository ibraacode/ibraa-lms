<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterTemplate extends Model
{
    protected $fillable = ['name', 'code', 'body'];

    public function letters()
    {
        return $this->hasMany(Letter::class);
    }
}