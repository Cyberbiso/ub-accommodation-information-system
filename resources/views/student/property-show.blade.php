@extends('layouts.app')

@section('title', $property->title)

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Property Details
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('student.off-campus') }}" class="text-red-800 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Properties
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content - Left Column -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="relative h-96 bg-gray-200">
                        @if($property->photos && count($property->photos) > 0)
                            <img src="{{ asset('storage/' . $property->photos[0]) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                <i class="fas fa-home text-gray-500 text-6xl"></i>
                            </div>
                        @endif
                        
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4 bg-red-800 text-white px-4 py-2 rounded-lg text-xl font-bold">
                            P{{ number_format($property->monthly_rent, 2) }}/month
                        </div>
                        
                        <!-- Availability Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 {{ $property->is_available ? 'bg-green-600' : 'bg-red-600' }} text-white rounded-lg text-sm">
                                {{ $property->is_available ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($property->photos && count($property->photos) > 1)
                        <div class="p-4 grid grid-cols-4 gap-2">
                            @foreach($property->photos as $index => $photo)
                                @if($index > 0)
                                    <img src="{{ asset('storage/' . $photo) }}" 
                                         alt="Property photo {{ $index + 1 }}"
                                         class="h-20 w-full object-cover rounded cursor-pointer hover:opacity-75 transition"
                                         onclick="showImage('{{ asset('storage/' . $photo) }}')">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h1 class="text-2xl font-bold mb-4">{{ $property->title }}</h1>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-bed text-red-800 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Bedrooms</p>
                            <p class="font-bold">{{ $property->bedrooms }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-bath text-red-800 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Bathrooms</p>
                            <p class="font-bold">{{ $property->bathrooms }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-ruler-combined text-red-800 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Type</p>
                            <p class="font-bold capitalize">{{ $property->type }}</p>
                        </div>
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-road text-red-800 text-xl mb-2"></i>
                            <p class="text-sm text-gray-600">Distance</p>
                            <p class="font-bold">{{ $property->distance_to_campus_km ?? 'N/A' }} km</p>
                        </div>
                    </div>

                    <h2 class="text-lg font-semibold mb-2">Description</h2>
                    <p class="text-gray-700 mb-6">{{ $property->description }}</p>

                    <h2 class="text-lg font-semibold mb-2">Address</h2>
                    <p class="text-gray-700 mb-6">{{ $property->address }}, {{ $property->city }}</p>

                    @if($property->amenities)
                        <h2 class="text-lg font-semibold mb-2">Amenities</h2>
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach(json_decode($property->amenities) ?? [] as $amenity)
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">{{ $amenity }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Landlord Info -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Landlord Information</h2>
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-800 rounded-full flex items-center justify-center text-white text-xl font-bold">
                            {{ substr($landlord->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="font-medium">{{ $landlord->name }}</p>
                            <p class="text-sm text-gray-500">Member since {{ $landlord->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-phone text-red-800 w-5"></i>
                            <span>{{ $landlord->phone ?? 'Contact via email' }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-red-800 w-5"></i>
                            <a href="mailto:{{ $landlord->email }}" class="text-red-800 hover:underline">
                                {{ $landlord->email }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Viewing Request Card -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Interested in this property?</h2>
                    
                    @if($existingRequest)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-yellow-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                You have a {{ $existingRequest->status }} viewing request for this property.
                            </p>
                            @if($existingRequest->status == 'pending')
                                <a href="{{ route('student.viewing-requests.cancel', $existingRequest) }}" 
                                   class="mt-2 text-sm text-red-600 hover:underline inline-block"
                                   onclick="return confirm('Are you sure you want to cancel this request?')">
                                    Cancel Request
                                </a>
                            @endif
                        </div>
                    @endif

                    <a href="{{ route('student.viewing-request.form', $property) }}" 
                       class="block w-full text-center bg-red-800 text-white px-4 py-3 rounded-lg hover:bg-red-900 transition mb-3">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Request Viewing
                    </a>
                    
                    <p class="text-xs text-gray-500 text-center">
                        You'll be able to select your preferred date and time
                    </p>
                </div>

                <!-- Similar Properties (Optional) -->
                @if(isset($similarProperties) && $similarProperties->count() > 0)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Similar Properties</h2>
                        <div class="space-y-3">
                            @foreach($similarProperties as $similar)
                                <a href="{{ route('student.properties.show', $similar) }}" 
                                   class="block p-3 border rounded-lg hover:bg-gray-50 transition">
                                    <p class="font-medium">{{ $similar->title }}</p>
                                    <p class="text-sm text-gray-600">P{{ number_format($similar->monthly_rent, 2) }}/month</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
    <div class="relative max-w-4xl mx-auto">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
            <i class="fas fa-times text-2xl"></i>
        </button>
        <img id="modalImage" src="" alt="Property image" class="max-h-screen max-w-full">
    </div>
</div>

<script>
    function showImage(src) {
        document.getElementById('modalImage').src = src;
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
    }

    // Close modal when clicking outside
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });
</script>
@endsection