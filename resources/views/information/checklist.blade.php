@extends('layouts.public')

@section('title', 'Onboarding Checklist')

@section('content')
<div class="bg-gradient-to-r from-red-800 to-red-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">Onboarding Checklist</h1>
        <p class="text-lg opacity-90">Your step-by-step guide to a smooth arrival</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @php
        $categories = [
            'before_arrival' => 'Before Arrival',
            'upon_arrival' => 'Upon Arrival',
            'first_week' => 'First Week',
            'ongoing' => 'Ongoing'
        ];
    @endphp

    @foreach($categories as $key => $label)
        @if(isset($checklist[$key]) && count($checklist[$key]) > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">{{ $label }}</h2>
            <div class="space-y-4">
                @foreach($checklist[$key] as $item)
                <div class="border rounded-lg p-4 {{ $item->is_mandatory ? 'bg-gray-50' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-1">
                            <input type="checkbox" disabled
                                   class="h-5 w-5 text-red-800 rounded border-gray-300 pointer-events-none cursor-default opacity-60">
                        </div>
                        <div class="ml-3">
                            <h3 class="font-medium">
                                {{ $item->title }}
                                @if($item->is_mandatory)
                                <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">Required</span>
                                @endif
                            </h3>
                            @if($item->description)
                            <p class="text-gray-600 mt-1">{{ $item->description }}</p>
                            @endif
                            
                            @if($item->subtasks)
                            <div class="mt-3">
                                <h4 class="text-sm font-medium mb-2">Subtasks:</h4>
                                <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                    @foreach(json_decode($item->subtasks) as $subtask)
                                    <li>{{ $subtask }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            @if($item->estimated_days)
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-clock mr-1"></i> Complete {{ $item->estimated_days }} days before arrival
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection
