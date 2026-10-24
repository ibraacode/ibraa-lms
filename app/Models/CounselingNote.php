<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselingNote extends Model
{
    protected $fillable = ['counseling_case_id', 'author_id', 'content', 'session_date'];

    protected function casts(): array
    {
        return ['session_date' => 'date'];
    }

    public function counselingCase()
    {
        return $this->belongsTo(CounselingCase::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}