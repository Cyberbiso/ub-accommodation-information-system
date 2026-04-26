<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBooking extends Model
{
    use HasFactory;

    const STATUS_PENDING_LANDLORD_REVIEW          = 'pending_landlord_review';
    const STATUS_APPROVED_AWAITING_LEASE          = 'approved_awaiting_lease';
    const STATUS_LEASE_PENDING_LANDLORD_APPROVAL  = 'lease_pending_landlord_approval';
    const STATUS_APPROVED_AWAITING_PAYMENT        = 'approved_awaiting_payment';
    const STATUS_CONFIRMED                        = 'confirmed';
    const STATUS_REJECTED                         = 'rejected';

    protected $fillable = [
        'booking_reference',
        'student_id',
        'property_id',
        'landlord_id',
        'status',
        'move_in_date',
        'lease_months',
        'occupants',
        'special_requests',
        'landlord_rejection_note',
        'landlord_reviewed_at',
        'quoted_rent',
        'deposit_amount',
        'total_amount',
        'signed_lease_path',
        'signed_lease_original_name',
        'signed_lease_submitted_at',
        'paid_at',
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'signed_lease_submitted_at' => 'datetime',
        'landlord_reviewed_at' => 'datetime',
        'paid_at' => 'datetime',
        'quoted_rent' => 'float',
        'deposit_amount' => 'float',
        'total_amount' => 'float',
        'lease_months' => 'integer',
        'occupants' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'payable');
    }

    // ─── Status helpers ───────────────────────────────────────────────────────

    public function isPendingLandlordReview(): bool
    {
        return $this->status === self::STATUS_PENDING_LANDLORD_REVIEW;
    }

    public function isApprovedAwaitingLease(): bool
    {
        return $this->status === self::STATUS_APPROVED_AWAITING_LEASE;
    }

    public function isLeasePendingLandlordApproval(): bool
    {
        return $this->status === self::STATUS_LEASE_PENDING_LANDLORD_APPROVAL;
    }

    public function isApprovedAwaitingPayment(): bool
    {
        return $this->status === self::STATUS_APPROVED_AWAITING_PAYMENT;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isActive(): bool
    {
        return !in_array($this->status, [self::STATUS_CONFIRMED, self::STATUS_REJECTED]);
    }

    // ─── Landlord actions ─────────────────────────────────────────────────────

    public function approveByLandlord(): bool
    {
        if ($this->status !== self::STATUS_PENDING_LANDLORD_REVIEW) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_APPROVED_AWAITING_LEASE,
            'landlord_reviewed_at' => now(),
        ]);
    }

    public function rejectByLandlord(?string $note = null): bool
    {
        if ($this->status !== self::STATUS_PENDING_LANDLORD_REVIEW) {
            return false;
        }

        return $this->update([
            'status' => self::STATUS_REJECTED,
            'landlord_rejection_note' => $note,
            'landlord_reviewed_at' => now(),
        ]);
    }

    // ─── Student lease submission ──────────────────────────────────────────────

    public function markLeaseSubmitted(): bool
    {
        if ($this->status !== self::STATUS_APPROVED_AWAITING_LEASE) {
            return false;
        }

        return $this->update(['status' => self::STATUS_LEASE_PENDING_LANDLORD_APPROVAL]);
    }

    // ─── Landlord lease approval ───────────────────────────────────────────────

    public function approveLease(): bool
    {
        if ($this->status !== self::STATUS_LEASE_PENDING_LANDLORD_APPROVAL) {
            return false;
        }

        return $this->update(['status' => self::STATUS_APPROVED_AWAITING_PAYMENT]);
    }

    public function rejectLease(?string $note = null): bool
    {
        if ($this->status !== self::STATUS_LEASE_PENDING_LANDLORD_APPROVAL) {
            return false;
        }

        return $this->update([
            'status'                    => self::STATUS_APPROVED_AWAITING_LEASE,
            'landlord_rejection_note'   => $note,
            'signed_lease_path'         => null,
            'signed_lease_original_name' => null,
            'signed_lease_submitted_at' => null,
        ]);
    }

    // ─── Payment confirmation ──────────────────────────────────────────────────

    public function confirm(): bool
    {
        if ($this->status === self::STATUS_CONFIRMED) {
            return true;
        }

        $property = Property::find($this->property_id);

        if (!$property) {
            return false;
        }

        $updated = Property::whereKey($this->property_id)
            ->where('available_units', '>', 0)
            ->decrement('available_units');

        if ($updated === 0) {
            return false;
        }

        $property->refresh();
        $property->update([
            'is_available' => $property->available_units > 0,
        ]);

        return $this->update([
            'status' => self::STATUS_CONFIRMED,
            'paid_at' => now(),
        ]);
    }

    public function hasSignedLease(): bool
    {
        return !empty($this->signed_lease_path);
    }
}
