<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Accommodation Model
 * 
 * Represents on-campus housing units managed by the university.
 * Each record is a specific room or unit with capacity, price, and facilities.
 * 
 * @package App\Models
 */
class Accommodation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These can be filled via create/update methods.
     *
     * @var array
     */
    protected $fillable = [
        'name',                 // Room identifier (e.g., "Block A - 101")
        'type',                 // single, shared, family
        'capacity',             // Maximum number of students
        'current_occupancy',    // Currently assigned students
        'monthly_rent',         // Monthly rent amount
        'facilities',           // JSON array of facilities
        'is_available',         // Whether room is available for application
        'block',                // Building block (A, B, C, etc.)
        'floor',                // Floor number
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_available' => 'boolean',    // Cast to boolean
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'monthly_rent' => 'float',
    ];

    protected function facilities(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->normalizeFacilities($value),
            set: fn ($value) => $this->encodeFacilities($value),
        );
    }

    // ==========================================
    // HELPER METHODS
    // ==========================================

    /**
     * Check if this accommodation has available space
     * 
     * @return bool True if current occupancy is less than capacity
     */
    public function hasSpace(): bool
    {
        return $this->current_occupancy < $this->capacity;
    }

    /**
     * Get number of available spaces in this accommodation
     * 
     * @return int Number of empty spots
     */
    public function availableSpaces(): int
    {
        return $this->capacity - $this->current_occupancy;
    }

    /**
     * Get occupancy rate as percentage
     * 
     * @return float Occupancy percentage (0-100)
     */
    public function occupancyRate(): float
    {
        if ($this->capacity === 0) {
            return 0;
        }
        return round(($this->current_occupancy / $this->capacity) * 100, 2);
    }

    /**
     * Get facilities as HTML badges (for views)
     * 
     * @return string HTML string of facility badges
     */
    public function getFacilitiesBadgesAttribute(): string
    {
        if (!$this->facilities) {
            return '';
        }
        
        $badges = '';
        foreach ($this->facilities as $facility) {
            $badges .= "<span class='badge bg-info me-1'>{$facility}</span>";
        }
        return $badges;
    }

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get all applications for this accommodation
     * One-to-Many: An accommodation can have many applications
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get all students currently residing in this accommodation
     * Many-to-Many through applications table with status 'approved'
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function currentResidents()
    {
        return $this->belongsToMany(User::class, 'applications')
                    ->wherePivot('status', 'approved')
                    ->withPivot('approved_at', 'duration_months')
                    ->withTimestamps();
    }

    // ==========================================
    // SCOPES (for query filtering)
    // ==========================================

    /**
     * Scope query to only show available accommodations
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                     ->whereColumn('current_occupancy', '<', 'capacity');
    }

    /**
     * Scope query to filter by accommodation type
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
     * Scope query to filter by maximum monthly rent
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
     * Scope query to filter by block
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $block
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInBlock($query, $block)
    {
        return $query->where('block', $block);
    }

    private function normalizeFacilities($value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $this->normalizeFacilities($decoded);
            }

            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }

        return [];
    }

    private function encodeFacilities($value): ?string
    {
        $normalized = $this->normalizeFacilities($value);

        return empty($normalized) ? null : json_encode($normalized);
    }
}
