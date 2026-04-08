<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LandlordVerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'path',
        'original_name',
        'status',
        'review_notes',
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

    public function getDocumentTypeLabelAttribute(): string
    {
        return match ($this->document_type) {
            'company_registration' => 'Company Registration',
            'tax_clearance' => 'Tax Clearance Certificate',
            'identity_document' => 'Director / Signatory ID',
            'property_ownership' => 'Property Ownership Document',
            default => ucfirst(str_replace('_', ' ', $this->document_type)),
        };
    }
}
