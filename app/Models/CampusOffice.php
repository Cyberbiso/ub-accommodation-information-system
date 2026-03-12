<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampusOffice extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_name', 'building', 'room_number', 'description',
        'phone', 'email', 'hours', 'map_location', 'category', 
        'sort_order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get formatted full location
     */
    public function getFullLocationAttribute(): string
    {
        return $this->building . ($this->room_number ? ', ' . $this->room_number : '');
    }

    /**
     * Scope to get offices by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get active offices only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}