<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmigrationRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'category', 'required_documents',
        'process_steps', 'office_responsible', 'link_to_form',
        'priority', 'deadline', 'is_active'
    ];

    protected $casts = [
        'required_documents' => 'array',
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            1 => 'bg-red-600 text-white',     // High priority
            2 => 'bg-yellow-500 text-white',  // Medium priority
            3 => 'bg-green-600 text-white',   // Low priority
            default => 'bg-gray-500 text-white',
        };
    }

    /**
     * Get requirements by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get high priority items
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 1);
    }
}