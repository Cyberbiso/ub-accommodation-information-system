<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'available_from',
        'distance_to_campus_km',
        'latitude',
        'longitude',
        'amenities',
        'transport_routes',
        'nearby_amenities',
        'navigation_notes',
        'photos',
        'lease_agreement_path',
        'lease_agreement_original_name',
        'lease_agreement_uploaded_at',
        'is_available',
        'is_approved',
        'review_status',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'listed_at',
    ];

    protected $casts = [
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
        'available_from' => 'date',
        'lease_agreement_uploaded_at' => 'datetime',
        'listed_at' => 'datetime',
    ];

    protected function amenities(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeListValue($value),
            set: fn ($value) => $this->encodeListValue($value),
        );
    }

    protected function transportRoutes(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeListValue($value),
            set: fn ($value) => $this->encodeListValue($value),
        );
    }

    protected function nearbyAmenities(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeListValue($value),
            set: fn ($value) => $this->encodeListValue($value),
        );
    }

    protected function photos(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeListValue($value, false),
            set: fn ($value) => $this->encodeListValue($value),
        );
    }

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
        $photos = $this->normalizeListValue($this->getRawOriginal('photos'), false);

        if (!empty($photos)) {
            return asset('storage/' . $photos[0]);
        }

        return asset('images/default-property.jpg');
    }

    public function getPhotoUrlsAttribute(): array
    {
        $photos = $this->normalizeListValue($this->getRawOriginal('photos'), false);

        if (empty($photos)) {
            return [];
        }

        return array_map(function ($photo) {
            return asset('storage/' . $photo);
        }, $photos);
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

    public function getAvailableFromLabelAttribute(): string
    {
        if (!$this->available_from) {
            return 'Availability date not set';
        }

        return 'Available from ' . $this->available_from->format('d M Y');
    }

    public function getEarliestMoveInDateAttribute(): string
    {
        $tomorrow = now()->addDay()->startOfDay();

        if ($this->available_from && $this->available_from->greaterThan($tomorrow)) {
            return $this->available_from->toDateString();
        }

        return $tomorrow->toDateString();
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

    public function hasLeaseAgreement(): bool
    {
        return !empty($this->lease_agreement_path);
    }

    private function normalizeListValue($value, bool $splitPlainStrings = true): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter(array_map(
                fn ($item) => is_string($item) ? trim($item) : $item,
                $value
            ), fn ($item) => $item !== null && $item !== ''));
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded)) {
                    return $this->normalizeListValue($decoded, $splitPlainStrings);
                }

                return $decoded ? [(string) $decoded] : [];
            }

            if ($splitPlainStrings) {
                return array_values(array_filter(array_map('trim', explode(',', $value))));
            }

            return [trim($value)];
        }

        return [];
    }

    private function encodeListValue($value): ?string
    {
        $normalized = $this->normalizeListValue($value, false);

        return empty($normalized) ? null : json_encode($normalized);
    }
}
