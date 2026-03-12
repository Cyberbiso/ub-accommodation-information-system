<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * ViewingRequest Model
 * 
 * Represents a student's request to view an off-campus property.
 * Landlords can approve, reject, and schedule viewing times.
 * 
 * @package App\Models
 */
class ViewingRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',           // Student requesting the viewing
        'property_id',          // Property to be viewed
        'landlord_id',          // Landlord who owns the property
        'preferred_date',       // When student wants to view
        'message',              // Optional message from student
        'status',               // pending, approved, rejected, completed
        'scheduled_date',       // Confirmed viewing time (if approved)
        'landlord_response',    // Response from landlord
    ];

    /**
     * The attributes that should be cast to dates.
     *
     * @var array
     */
    protected $casts = [
        'preferred_date' => 'datetime',
        'scheduled_date' => 'datetime',
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
     * Approve this viewing request
     * Sets status to approved and stores scheduled date
     * 
     * @param \DateTime|string $scheduledDate Confirmed viewing time
     * @param string|null $response Optional message from landlord
     * @return bool
     */
    public function approve($scheduledDate, $response = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'scheduled_date' => $scheduledDate,
            'landlord_response' => $response,
        ]);
    }

    /**
     * Reject this viewing request
     * Sets status to rejected and stores reason
     * 
     * @param string $reason Reason for rejection
     * @return bool
     */
    public function reject(string $reason): bool
    {
        return $this->update([
            'status' => 'rejected',
            'landlord_response' => $reason,
        ]);
    }

    /**
     * Mark viewing as completed (after it happens)
     * 
     * @return bool
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => 'completed',
        ]);
    }

    /**
     * Check if request is pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved
     * 
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
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
            'completed' => 'bg-primary',
            default => 'bg-warning',
        };
    }

    /**
     * Get formatted preferred date for display
     * 
     * @return string
     */
    public function getFormattedPreferredDateAttribute(): string
    {
        return $this->preferred_date->format('M d, Y \a\t h:i A');
    }

    /**
     * Get formatted scheduled date for display
     * 
     * @return string|null
     */
    public function getFormattedScheduledDateAttribute(): ?string
    {
        return $this->scheduled_date?->format('M d, Y \a\t h:i A');
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the student who made this request
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the landlord who received this request
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the property being requested for viewing
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the payment associated with this viewing request
     * Polymorphic: A viewing request can have one payment (if fees apply)
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
     * Scope to only show pending requests for a landlord
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $landlordId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePendingForLandlord($query, $landlordId)
    {
        return $query->where('landlord_id', $landlordId)
                     ->where('status', 'pending');
    }

    /**
     * Scope to only show requests for a specific student
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
     * Scope to only show requests for a specific property
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $propertyId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForProperty($query, $propertyId)
    {
        return $query->where('property_id', $propertyId);
    }

    /**
     * Scope to filter by status
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get upcoming approved viewings
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'approved')
                     ->where('scheduled_date', '>=', now())
                     ->orderBy('scheduled_date', 'asc');
    }
}