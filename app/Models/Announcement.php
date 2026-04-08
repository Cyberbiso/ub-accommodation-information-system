<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'content',
        'target_role',
        'priority',
        'is_published',
        'published_at',
        'expires_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_published', true)
            ->where(function ($publishedQuery) {
                $publishedQuery->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            })
            ->where(function ($activeQuery) {
                $activeQuery->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->orderByDesc('published_at');
    }
}
