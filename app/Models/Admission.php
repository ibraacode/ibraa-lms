<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
        protected $fillable = [
        'registration_number', 'full_name', 'nisn', 'gender', 'birth_place', 'birth_date',
        'previous_school', 'phone', 'email', 'address', 'parent_name', 'parent_phone',
        'chosen_major', 'status', 'notes', 'converted_user_id',
        // Tambahan
        'religion', 'child_order', 'siblings_count', 'living_with',
        'father_occupation', 'mother_occupation', 'father_education', 'mother_education', 'parent_address',
        'document_kk', 'document_akta', 'document_ijazah', 'document_photo',
        'is_documents_verified', 'verified_by', 'verified_at',
        'is_fee_paid', 'payment_proof',
          'report_score', 'test_score', 'final_score',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'is_documents_verified' => 'boolean',
            'is_fee_paid' => 'boolean',
        ];
    }

    public function convertedUser()
    {
        return $this->belongsTo(User::class, 'converted_user_id');
    }
}