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
        'student_category',
        'surname',
        'first_name',
        'nationality',
        'country_of_origin',
        'passport_number',
        'immigration_status',
        'company_name',
        'company_registration_number',
        'tax_identification_number',
        'phone',
        'acceptance_letter',
        'proof_of_registration',
        'passport_copy',
        'document_status',
        'documents_verified_at',
        'verified_by',
        'verification_notes',
        'landlord_verification_status',
        'landlord_verification_stage',
        'landlord_verification_submitted_at',
        'landlord_verified_at',
        'landlord_verification_reviewed_by',
        'landlord_verification_reviewed_at',
        'is_active',
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
            'landlord_verification_submitted_at' => 'datetime',
            'landlord_verified_at' => 'datetime',
            'landlord_verification_reviewed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

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

    public function isInternational(): bool
    {
        return $this->student_category === 'international';
    }

    public function isVerifiedLandlord(): bool
    {
        return $this->isLandlord()
            && $this->landlord_verification_status === 'verified'
            && $this->landlord_verified_at !== null;
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function landlordVerificationDocuments()
    {
        return $this->hasMany(LandlordVerificationDocument::class);
    }

    public function notifications()
    {
        return $this->hasMany(SystemNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(SystemNotification::class)->whereNull('read_at');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function getDocument($type)
    {
        return $this->documents()->where('document_type', $type)->latest()->first();
    }

    public function hasRequiredDocuments()
    {
        $required = ['acceptance_letter', 'proof_of_registration'];

        if ($this->isInternational()) {
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
        $required = ['acceptance_letter', 'proof_of_registration'];

        if ($this->isInternational()) {
            $required[] = 'passport';
        }

        $statuses = [];

        foreach ($required as $type) {
            $doc = $this->getDocument($type);
            $statuses[] = $doc?->status ?? 'pending';
        }

        if (in_array('rejected', $statuses, true)) {
            return 'rejected';
        }

        if (count(array_filter($statuses, fn ($status) => $status === 'verified')) === count($statuses)) {
            return 'verified';
        }

        if (in_array('verified', $statuses, true)) {
            return 'partial';
        }

        return 'pending';
    }

    public function landlordVerificationSteps(): array
    {
        return [
            'company_registration' => 'Company registration',
            'tax_clearance' => 'Tax clearance certificate',
            'identity_document' => 'Director or signatory ID',
            'property_ownership' => 'Property ownership documentation',
        ];
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

    public function propertyBookings()
    {
        return $this->hasMany(PropertyBooking::class, 'student_id');
    }

    public function landlordBookings()
    {
        return $this->hasMany(PropertyBooking::class, 'landlord_id');
    }

    public function processedApplications()
    {
        return $this->hasMany(Application::class, 'processed_by');
    }

    public function supportRequests()
    {
        return $this->hasMany(SupportRequest::class, 'student_id');
    }

    public function assignedSupportRequests()
    {
        return $this->hasMany(SupportRequest::class, 'assigned_to');
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function propertyEnquiries()
    {
        return $this->hasMany(PropertyEnquiry::class, 'student_id');
    }

    public function landlordEnquiries()
    {
        return $this->hasMany(PropertyEnquiry::class, 'landlord_id');
    }
}
