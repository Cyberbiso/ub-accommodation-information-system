<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Payment Model
 * 
 * Represents all financial transactions in the system.
 * Uses polymorphic relationship to link to applications or viewing requests.
 * 
 * @package App\Models
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',           // Student making the payment
        'payable_type',         // Model type (App\Models\Application or App\Models\ViewingRequest)
        'payable_id',           // ID of the related model
        'amount',               // Payment amount
        'type',                 // application_fee, deposit, rent, viewing_fee
        'status',               // pending, completed, failed, refunded
        'payment_method',       // card, bank_transfer, cash
        'transaction_id',       // External transaction reference
        'payment_details',      // JSON with additional data
        'paid_at',              // When payment was completed
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'float',
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
     * Mark payment as completed
     * 
     * @param string|null $transactionId External transaction reference
     * @return bool
     */
    public function markAsCompleted($transactionId = null): bool
    {
        return $this->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     * 
     * @return bool
     */
    public function markAsFailed(): bool
    {
        return $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Mark payment as refunded
     * 
     * @return bool
     */
    public function markAsRefunded(): bool
    {
        return $this->update([
            'status' => 'refunded',
        ]);
    }

    /**
     * Check if payment is completed
     * 
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending
     * 
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Get status badge class for UI
     * 
     * @return string Bootstrap badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'failed' => 'bg-danger',
            'refunded' => 'bg-info',
            default => 'bg-warning',
        };
    }

    /**
     * Get payment type label
     * 
     * @return string
     */
    public function getTypeLabelAttribute(): string
    {
        if ($this->payable_type === PropertyBooking::class) {
            return 'Off-Campus Accommodation Payment';
        }

        return match($this->type) {
            'application_fee' => 'Application Fee',
            'deposit' => 'Security Deposit',
            'rent' => 'Rent Payment',
            'viewing_fee' => 'Viewing Fee',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get formatted amount with currency
     * 
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'P' . number_format($this->amount, 2);
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the parent payable model (polymorphic)
     * Can be Application or ViewingRequest
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }

    /**
     * Get the student who made this payment
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to only show completed payments
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to only show pending payments
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to filter by student
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
     * Scope to filter by payment type
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by date range
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

    /**
     * Scope to filter by paid date range
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaidBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('paid_at', [$startDate, $endDate]);
    }
}
