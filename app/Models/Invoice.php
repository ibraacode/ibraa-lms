<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['academic_year','invoice_number', 'student_id', 'fee_type_id', 'amount', 'due_date', 'status', 'notes'];

    protected function casts(): array
    {
        return ['due_date' => 'date'];
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid()
    {
        return $this->payments->sum('amount_paid');
    }

    public function remaining()
    {
        return max(0, $this->amount - $this->totalPaid());
    }
}