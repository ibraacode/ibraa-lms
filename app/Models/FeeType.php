<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeType extends Model
{
    protected $fillable = ['name', 'description', 'default_amount', 'is_mandatory'];

    protected function casts(): array
    {
        return ['is_mandatory' => 'boolean'];
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}