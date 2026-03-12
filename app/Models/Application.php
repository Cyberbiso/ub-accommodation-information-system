<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Application Model
 * 
 * Represents a student's application for on-campus accommodation.
 * Tracks the entire process from submission to approval/rejection.
 * 
 * @package App\Models
 */
class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',               // ID of student applying
        'accommodation_id',         // ID of accommodation applied for
        'status',                   // pending, approved, rejected, waitlisted
        'preferred_move_in_date',    // When student wants to move in
        'duration_months',           // Length of stay in months
        'special_requirements',      // Disabilities, preferences, etc.
        'rejection_reason',          // Why application was rejected
        'approved_at',               // When it was approved
        'processed_by',              // Welfare officer who processed it
         'has_disability',
         'medical_certificate',
         'medical_status',
         'disability_notes',
         'form_data', // Add this
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array
     */
    protected $casts = [
        'preferred_move_in_date' => 'date',
        'approved_at' => 'datetime',
        'duration_months' => 'integer',
        'has_disability' => 'boolean',
        'form_data' => 'array', 
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    // ==========================================
    // BUSINESS LOGIC METHODS
    // ==========================================

    /**
     * Approve this application
     * Updates status and increases accommodation occupancy
     * 
     * @param int $welfareOfficerId ID of welfare officer processing
     * @return bool Success status
     */
    public function approve(int $welfareOfficerId): bool
    {
        // Update application status
        $this->update([
            'status' => 'approved',
            'processed_by' => $welfareOfficerId,
            'approved_at' => now(),
        ]);

        // Increase occupancy of the accommodation
        if ($this->accommodation) {
            $this->accommodation->increment('current_occupancy');
        }
        
        return true;
    }

    /**
     * Reject this application
     * Updates status and stores rejection reason
     * 
     * @param int $welfareOfficerId ID of welfare officer
     * @param string $reason Reason for rejection
     * @return bool Success status
     */
    public function reject(int $welfareOfficerId, string $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'processed_by' => $welfareOfficerId,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Place application on waitlist
     * 
     * @param int $welfareOfficerId ID of welfare officer
     * @return bool Success status
     */
    public function waitlist(int $welfareOfficerId): bool
    {
        return $this->update([
            'status' => 'waitlisted',
            'processed_by' => $welfareOfficerId,
        ]);
    }

    /**
     * Check if application is pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is approved
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if application is rejected
     * 
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Get status badge class for UI
     * 
     * @return string Bootstrap badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'waitlisted' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the student who submitted this application
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the accommodation being applied for
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    /**
     * Get the welfare officer who processed this application
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Get the payment associated with this application
     * Polymorphic: An application can have one payment
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope query to only show pending applications
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope query to only show approved applications
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope query to filter by student
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope query to filter by accommodation
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $accommodationId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAccommodation($query, $accommodationId)
    {
        return $query->where('accommodation_id', $accommodationId);
    }

    /**
     * Scope query to filter by date range
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}