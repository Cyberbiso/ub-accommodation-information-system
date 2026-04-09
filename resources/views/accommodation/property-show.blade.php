@extends('layouts.public')

@section('title', $property->title)

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $property->title }}</h1>
                <p class="text-lg opacity-90">{{ $property->city }}</p>
            </div>
            <a href="{{ route('properties.index') }}" class="text-white hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Properties
            </a>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="p-8">
            <!-- Property Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column - Details -->
                <div>
                    <h2 class="text-2xl font-bold mb-4">Property Details</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Monthly Rent:</div>
                            <div class="font-bold text-xl text-red-800">P{{ number_format($property->monthly_rent, 2) }}</div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Location:</div>
                            <div>{{ $property->address }}, {{ $property->city }}</div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Property Type:</div>
                            <div class="capitalize">{{ $property->type }}</div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Bedrooms:</div>
                            <div>{{ $property->bedrooms }}</div>
                        </div>
                        
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Bathrooms:</div>
                            <div>{{ $property->bathrooms }}</div>
                        </div>
                        
                        @if($property->distance_to_campus_km)
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Distance:</div>
                            <div>{{ $property->distance_to_campus_km }} km from campus</div>
                        </div>
                        @endif
                        
                        <div class="flex items-center">
                            <div class="w-32 text-gray-600">Landlord:</div>
                            <div>{{ $property->landlord->name }}</div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-700">{{ $property->description }}</p>
                    </div>
                    
                    <!-- Amenities -->
                    @if($property->amenities)
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Amenities</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($property->amenities ?? [] as $amenity)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="mt-8 space-y-3">
                        @if($property->navigation_url)
                            <a href="{{ $property->navigation_url }}"
                               target="_blank"
                               rel="noreferrer"
                               class="block w-full text-center border border-red-200 bg-red-50 text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-red-100 transition">
                                <i class="fas fa-route mr-2"></i>Open in Google Maps
                            </a>
                        @else
                            <div class="block w-full text-center bg-yellow-50 text-yellow-800 px-6 py-3 rounded-lg">
                                Google Maps directions will appear here once a location pin has been added for this property.
                            </div>
                        @endif

                        @auth
                            @if(Auth::user()->isStudent())
                                <a href="{{ route('student.properties.show', $property) }}" 
                                   class="block w-full text-center bg-red-800 text-white px-6 py-3 rounded-lg hover:bg-red-900 transition">
                                    Open Student Booking & Viewing Tools
                                </a>
                            @else
                                <div class="block w-full text-center bg-gray-100 text-gray-700 px-6 py-3 rounded-lg">
                                    Only student accounts can request viewings or book this property
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-red-800 text-white px-6 py-3 rounded-lg hover:bg-red-900 transition">
                                Login as Student to Continue
                            </a>
                        @endauth
                    </div>
                </div>
                
                <!-- Right Column - Placeholder for Photos -->
                <div>
                    <h2 class="text-2xl font-bold mb-4">Photos</h2>
                    @if(count($property->photo_urls) > 0)
                        <div class="grid grid-cols-1 gap-4">
                            <img src="{{ $property->first_photo }}" alt="{{ $property->title }}" class="w-full h-80 object-cover rounded-lg">
                            @foreach(collect($property->photo_urls)->slice(1, 3) as $photo)
                                <img src="{{ $photo }}" alt="{{ $property->title }}" class="w-full h-40 object-cover rounded-lg">
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-200 h-96 rounded-lg flex items-center justify-center">
                            <p class="text-gray-500">Property photos will appear here</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
