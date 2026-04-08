<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'title',
        'description',
        'address',
        'city',
        'postal_code',
        'monthly_rent',
        'deposit_amount',
        'type',
        'bedrooms',
        'bathrooms',
        'available_units',
        'distance_to_campus_km',
        'latitude',
        'longitude',
        'amenities',
        'transport_routes',
        'nearby_amenities',
        'navigation_notes',
        'photos',
        'is_available',
        'is_approved',
        'review_status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'listed_at',
    ];

    protected $casts = [
        'amenities' => 'array',
        'transport_routes' => 'array',
        'nearby_amenities' => 'array',
        'photos' => 'array',
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
        'reviewed_at' => 'datetime',
        'distance_to_campus_km' => 'float',
        'monthly_rent' => 'float',
        'deposit_amount' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'available_units' => 'integer',
        'listed_at' => 'datetime',
    ];

    public function getAmenitiesBadgesAttribute(): string
    {
        if (!$this->amenities || !is_array($this->amenities)) {
            return '';
        }

        $badges = '';
        foreach ($this->amenities as $amenity) {
            $badges .= "<span class='badge bg-info me-1 mb-1'>{$amenity}</span>";
        }

        return $badges;
    }

    public function getFirstPhotoAttribute(): string
    {
        if ($this->photos && count($this->photos) > 0) {
            return asset('storage/' . $this->photos[0]);
        }

        return asset('images/default-property.jpg');
    }

    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }

        return array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}" .
            ($this->postal_code ? ", {$this->postal_code}" : '');
    }

    public function getCampusDistanceLabelAttribute(): string
    {
        if ($this->distance_to_campus_km === null) {
            return 'Distance unavailable';
        }

        return number_format($this->distance_to_campus_km, 1) . ' km from campus';
    }

    public function getNavigationUrlAttribute(): ?string
    {
        if (!$this->hasCoordinates()) {
            return null;
        }

        return 'https://www.google.com/maps/dir/?api=1&destination='
            . $this->latitude . ',' . $this->longitude;
    }

    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    public function viewingRequests()
    {
        return $this->hasMany(ViewingRequest::class);
    }

    public function pendingViewingRequests()
    {
        return $this->hasMany(ViewingRequest::class)->where('status', 'pending');
    }

    public function bookings()
    {
        return $this->hasMany(PropertyBooking::class);
    }

    public function enquiries()
    {
        return $this->hasMany(PropertyEnquiry::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true)->where('review_status', 'approved');
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
            ->where('available_units', '>', 0);
    }

    public function scopeLive($query)
    {
        return $query->approved()->available();
    }

    public function scopeInCity($query, $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }

    public function scopeMaxPrice($query, $maxPrice)
    {
        return $query->where('monthly_rent', '<=', $maxPrice);
    }

    public function scopeMinPrice($query, $minPrice)
    {
        return $query->where('monthly_rent', '>=', $minPrice);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->where('bedrooms', '>=', $bedrooms);
    }

    public function scopeMaxDistance($query, $maxDistance)
    {
        return $query->where('distance_to_campus_km', '<=', $maxDistance);
    }

    public function scopeNearest($query)
    {
        return $query->orderBy('distance_to_campus_km', 'asc');
    }

    public function scopeCheapest($query)
    {
        return $query->orderBy('monthly_rent', 'asc');
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }
}
