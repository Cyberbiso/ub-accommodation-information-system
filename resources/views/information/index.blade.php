@extends('layouts.public')

@section('title', 'Information Hub')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-red-800 to-red-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-4">Information Hub</h1>
        <p class="text-xl opacity-90 max-w-3xl">
            Everything you need to know before and after arrival at the University of Botswana.
            Find campus offices, immigration requirements, and essential resources.
        </p>
    </div>
</div>

<!-- Quick Links Cards -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Campus Directory Card -->
        <a href="{{ route('information.campus-directory') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border border-gray-200">
            <div class="text-red-800 text-3xl mb-3">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Campus Directory</h3>
            <p class="text-gray-600 text-sm">Find offices, departments, and key locations on campus</p>
        </a>
        
        <!-- Immigration Card -->
        <a href="{{ route('information.immigration') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border border-gray-200">
            <div class="text-red-800 text-3xl mb-3">
                <i class="fas fa-passport"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Immigration Compliance</h3>
            <p class="text-gray-600 text-sm">Visa requirements, permits, and important deadlines</p>
        </a>
        
        <!-- Onboarding Checklist Card -->
        <a href="{{ route('information.checklist') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border border-gray-200">
            <div class="text-red-800 text-3xl mb-3">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Onboarding Checklist</h3>
            <p class="text-gray-600 text-sm">Step-by-step guide for before and after arrival</p>
        </a>
        
        <!-- Resources Card -->
        <a href="{{ route('information.resources') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition border border-gray-200">
            <div class="text-red-800 text-3xl mb-3">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="text-lg font-semibold mb-2">Resources Library</h3>
            <p class="text-gray-600 text-sm">Downloadable guides, forms, and helpful links</p>
        </a>
    </div>
</div>

<!-- Campus Offices Preview -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Key Campus Offices</h2>
        <a href="{{ route('information.campus-directory') }}" class="text-red-800 hover:text-red-900 font-medium">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $offices = App\Models\CampusOffice::active()->orderBy('sort_order')->take(6)->get();
        @endphp
        
        @foreach($offices as $office)
        <div class="border rounded-lg p-4 hover:shadow-md transition">
            <h3 class="font-semibold text-lg">{{ $office->office_name }}</h3>
            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($office->description, 80) }}</p>
            <div class="mt-3 space-y-1 text-sm">
                <p><i class="fas fa-map-marker-alt text-red-800 mr-2 w-4"></i> {{ $office->building }} {{ $office->room_number ? ', ' . $office->room_number : '' }}</p>
                @if($office->phone)
                <p><i class="fas fa-phone text-red-800 mr-2 w-4"></i> {{ $office->phone }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection