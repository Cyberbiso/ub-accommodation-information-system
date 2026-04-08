<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyBooking extends Model
{
    use HasFactory;

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
        'quoted_rent',
        'deposit_amount',
        'total_amount',
        'paid_at',
    ];

    protected $casts = [
        'move_in_date' => 'date',
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

    public function confirm(): bool
    {
        if ($this->status === 'confirmed') {
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
            'status' => 'confirmed',
            'paid_at' => now(),
        ]);
    }
}
