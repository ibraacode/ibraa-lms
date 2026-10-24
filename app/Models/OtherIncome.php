<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherIncome extends Model
{
    protected $fillable = ['source', 'description', 'amount', 'received_at', 'reference', 'recorded_by'];

    protected function casts(): array
    {
        return ['received_at' => 'date'];
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}