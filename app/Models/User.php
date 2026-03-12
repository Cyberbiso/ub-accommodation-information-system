<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'student_id',
    'surname',
    'first_name',
    'company_name',
    'phone',
    'acceptance_letter',
    'proof_of_registration',
    'passport_copy',
    'document_status',
    'documents_verified_at',
    'verified_by',
    'verification_notes',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'documents_verified_at' => 'datetime',
        ];
    }

    // Role Methods
    public function hasRole($role): bool
    {
        return $this->role === $role;
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isLandlord(): bool
    {
        return $this->role === 'landlord';
    }

    public function isWelfare(): bool
    {
        return $this->role === 'welfare';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Relationships
    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function getDocument($type)
    {
        return $this->documents()->where('document_type', $type)->latest()->first();
    }

    public function hasRequiredDocuments()
    {
        $required = ['acceptance_letter', 'proof_of_registration'];
        $isInternational = !str_contains($this->email, '.bw');
        
        if ($isInternational) {
            $required[] = 'passport';
        }
        
        foreach ($required as $type) {
            $doc = $this->getDocument($type);
            if (!$doc || $doc->status !== 'verified') {
                return false;
            }
        }
        
        return true;
    }

    public function getDocumentStatusAttribute()
    {
        $total = 0;
        $verified = 0;
        
        $required = ['acceptance_letter', 'proof_of_registration'];
        $isInternational = !str_contains($this->email, '.bw');
        
        if ($isInternational) {
            $required[] = 'passport';
        }
        
        foreach ($required as $type) {
            $total++;
            $doc = $this->getDocument($type);
            if ($doc && $doc->status === 'verified') {
                $verified++;
            }
        }
        
        if ($verified === $total) {
            return 'verified';
        } elseif ($verified > 0) {
            return 'partial';
        } else {
            return 'pending';
        }
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'student_id');
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'landlord_id');
    }

    public function viewingRequests()
    {
        return $this->hasMany(ViewingRequest::class, 'student_id');
    }

    public function landlordViewingRequests()
    {
        return $this->hasMany(ViewingRequest::class, 'landlord_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function processedApplications()
    {
        return $this->hasMany(Application::class, 'processed_by');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}