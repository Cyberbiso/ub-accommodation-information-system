<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Property Model
 * 
 * Represents off-campus property listings from landlords.
 * Contains all details, photos, and amenities for student viewing.
 * 
 * @package App\Models
 */
class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'landlord_id',              // ID of landlord who owns this property
        'title',                    // Property title/headline
        'description',              // Full description
        'address',                  // Street address
        'city',                      // City
        'postal_code',               // Postal/ZIP code
        'monthly_rent',              // Monthly rent amount
        'type',                      // apartment, house, shared, studio
        'bedrooms',                  // Number of bedrooms
        'bathrooms',                 // Number of bathrooms
        'distance_to_campus_km',     // Distance from university
        'amenities',                 // JSON array of amenities
        'photos',                    // JSON array of photo paths
        'is_available',              // Whether property is currently available
        'is_approved',               // Whether admin has approved this listing
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amenities' => 'array',
        'photos' => 'array',
        'is_available' => 'boolean',
        'is_approved' => 'boolean',
        'distance_to_campus_km' => 'float',
        'monthly_rent' => 'float',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
    ];

    // ==========================================
    // ACCESSORS & MUTATORS
    // ==========================================

    /**
     * Get amenities as HTML badges
     * 
     * @return string HTML string of amenity badges
     */
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

    /**
     * Get the first photo or default image
     * 
     * @return string URL to photo
     */
    public function getFirstPhotoAttribute(): string
    {
        if ($this->photos && count($this->photos) > 0) {
            return asset('storage/' . $this->photos[0]);
        }
        return asset('images/default-property.jpg');
    }

    /**
     * Get all photos as array of URLs
     * 
     * @return array
     */
    public function getPhotoUrlsAttribute(): array
    {
        if (!$this->photos) {
            return [];
        }
        
        return array_map(function($photo) {
            return asset('storage/' . $photo);
        }, $this->photos);
    }

    /**
     * Get formatted address (single line)
     * 
     * @return string
     */
    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}" . 
               ($this->postal_code ? ", {$this->postal_code}" : '');
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the landlord who owns this property
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get all viewing requests for this property
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function viewingRequests()
    {
        return $this->hasMany(ViewingRequest::class);
    }

    /**
     * Get all pending viewing requests for this property
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pendingViewingRequests()
    {
        return $this->hasMany(ViewingRequest::class)
                    ->where('status', 'pending');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    /**
     * Scope to only show approved properties
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to only show available properties
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to filter by city
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', 'LIKE', "%{$city}%");
    }

    /**
     * Scope to filter by maximum price
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $maxPrice
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaxPrice($query, $maxPrice)
    {
        return $query->where('monthly_rent', '<=', $maxPrice);
    }

    /**
     * Scope to filter by minimum price
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $minPrice
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinPrice($query, $minPrice)
    {
        return $query->where('monthly_rent', '>=', $minPrice);
    }

    /**
     * Scope to filter by property type
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
     * Scope to filter by minimum bedrooms
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $bedrooms
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinBedrooms($query, $bedrooms)
    {
        return $query->where('bedrooms', '>=', $bedrooms);
    }

    /**
     * Scope to filter by maximum distance from campus
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param float $maxDistance
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMaxDistance($query, $maxDistance)
    {
        return $query->where('distance_to_campus_km', '<=', $maxDistance);
    }

    /**
     * Scope to order by nearest to campus
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearest($query)
    {
        return $query->orderBy('distance_to_campus_km', 'asc');
    }

    /**
     * Scope to order by cheapest first
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCheapest($query)
    {
        return $query->orderBy('monthly_rent', 'asc');
    }
}