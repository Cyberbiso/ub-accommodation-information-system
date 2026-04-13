@extends('layouts.public')

@section('title', 'Off-Campus Properties')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-4">Off-Campus Properties</h1>
        <p class="text-xl mb-8 max-w-3xl">
            Browse verified properties from trusted landlords near campus.
        </p>
        <a href="{{ route('home') }}" class="text-white hover:underline">
            <i class="fas fa-arrow-left mr-2"></i>Back to Home
        </a>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="city" value="{{ request('city') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800"
                       placeholder="City or area">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                <select name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                    <option value="">All Types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Max Price (P)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800"
                       placeholder="e.g., 3000">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Min Bedrooms</label>
                <input type="number" name="bedrooms" value="{{ request('bedrooms') }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800"
                       placeholder="e.g., 1">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Properties Grid -->
    @if($properties->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-200">
                    <div class="h-2 bg-red-800"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $property->title }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-800 w-5"></i>
                                <span>{{ $property->city }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag text-red-800 w-5"></i>
                                <span>P{{ number_format($property->monthly_rent, 2) }}/month</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-bed text-red-800 w-5"></i>
                                <span>{{ $property->bedrooms }} bed • {{ $property->bathrooms }} bath</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt text-red-800 w-5"></i>
                                <span>{{ $property->available_from_label }}</span>
                            </div>
                            @if($property->distance_to_campus_km)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-road text-red-800 w-5"></i>
                                    <span>{{ $property->distance_to_campus_km }} km from campus</span>
                                </div>
                            @endif
                        </div>
                        
                        @if($property->amenities)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Amenities:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($property->amenities ?? [] as $amenity)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">{{ $amenity }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @auth
                            @if(Auth::user()->isStudent())
                                <a href="{{ route('student.properties.show', $property) }}" 
                                   class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                    View Details
                                </a>
                            @else
                                <a href="{{ route('properties.show', $property) }}" 
                                   class="block w-full text-center border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                                    View Details
                                </a>
                            @endif
                        @else
                            <a href="{{ route('properties.show', $property) }}" 
                               class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                View Details
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-building"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No Properties Available</h3>
            <p class="text-gray-500">Check back later for new listings.</p>
        </div>
    @endif
</div>
@endsection
