<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnboardingChecklist extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'estimated_days',
        'subtasks',
        'resources',
        'sort_order',
        'is_mandatory',
        'is_active',
    ];

    protected $casts = [
        'subtasks' => 'array',
        'resources' => 'array',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];
}
