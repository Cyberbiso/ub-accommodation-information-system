<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $table = 'student_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'path',           
        'original_name',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getDocumentTypeLabelAttribute()
    {
        $labels = [
            'acceptance_letter' => 'Acceptance Letter',
            'proof_of_registration' => 'Proof of Registration',
            'passport' => 'Passport Copy',
            'medical_certificate' => 'Medical Certificate',
        ];
        
        return $labels[$this->document_type] ?? ucfirst(str_replace('_', ' ', $this->document_type));
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'verified' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-yellow-100 text-yellow-800',
        };
    }
}