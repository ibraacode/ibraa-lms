<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryPlacement extends Model
{
    protected $fillable = ['company_name', 'address', 'contact_person', 'phone', 'major_code'];

    public function internships()
    {
        return $this->hasMany(InternshipLog::class);
    }
}