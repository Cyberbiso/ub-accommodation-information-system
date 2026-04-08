@extends('layouts.app')

@section('title', 'Off-Campus Properties')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Off-Campus Properties
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white rounded-lg shadow-lg mb-6 p-6">
            <h1 class="text-2xl font-bold mb-2">Off-Campus Properties</h1>
            <p class="opacity-90">Browse verified listings from trusted landlords near campus.</p>
        </div>

        <!-- Advanced Filters -->
        <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
            <form method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                        <select name="city" class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                            <option value="">All Locations</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                    {{ $city }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                        <select name="type" class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Min Price (P)</label>
                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800"
                               placeholder="e.g., 1000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Max Price (P)</label>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                               class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800"
                               placeholder="e.g., 5000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                        <select name="bedrooms" class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                            <option value="">Any</option>
                            <option value="1" {{ request('bedrooms') == '1' ? 'selected' : '' }}>1+</option>
                            <option value="2" {{ request('bedrooms') == '2' ? 'selected' : '' }}>2+</option>
                            <option value="3" {{ request('bedrooms') == '3' ? 'selected' : '' }}>3+</option>
                            <option value="4" {{ request('bedrooms') == '4' ? 'selected' : '' }}>4+</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Amenities Filter -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($allAmenities as $amenity)
                            <label class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                                       {{ in_array($amenity, (array)request('amenities', [])) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                                <span class="ml-2 text-sm text-gray-600">{{ $amenity }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('student.off-campus') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Clear Filters
                    </a>
                    <button type="submit" class="bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900">
                        <i class="fas fa-search mr-2"></i>Search Properties
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        <div class="mb-4">
            <p class="text-gray-600">Found <span class="font-bold">{{ $properties->total() }}</span> properties</p>
        </div>

        <!-- Properties Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($properties as $property)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-48 bg-gray-200 relative">
                        @if($property->photos && count($property->photos) > 0)
                            <img src="{{ asset('storage/' . $property->photos[0]) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-home text-gray-500 text-4xl"></i>
                            </div>
                        @endif
                        <div class="absolute top-2 right-2 bg-red-800 text-white px-2 py-1 rounded text-sm">
                            P{{ number_format($property->monthly_rent, 0) }}/mo
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $property->title }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-800 w-5"></i>
                                <span>{{ $property->city }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-bed text-red-800 w-5"></i>
                                <span>{{ $property->bedrooms }} beds • {{ $property->bathrooms }} baths</span>
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
                                <div class="flex flex-wrap gap-2">
                                    @foreach(array_slice($property->amenities ?? [], 0, 3) as $amenity)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">{{ $amenity }}</span>
                                    @endforeach
                                    @if(count($property->amenities ?? []) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                                            +{{ count($property->amenities) - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="flex space-x-2">
                            <a href="{{ route('student.properties.show', $property) }}" 
                               class="flex-1 text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                View Details
                            </a>
                            <a href="{{ route('student.properties.show', $property) }}" 
                               class="px-4 py-2 border border-red-800 text-red-800 rounded-lg hover:bg-red-50 transition">
                                <i class="fas fa-calendar-check"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-lg">
                    <i class="fas fa-building text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No Properties Found</h3>
                    <p class="text-gray-500">Try adjusting your filters or check back later.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $properties->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection
