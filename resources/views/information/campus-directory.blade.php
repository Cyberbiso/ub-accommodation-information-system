@extends('layouts.public')

@section('title', 'Campus Directory')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">Campus Directory</h1>
        <p class="text-lg opacity-90">Find offices, departments, and key locations</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- CAMPUS MAP SECTION -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-red-800 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-map mr-2"></i>
                Campus Map
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-gray-100 rounded-lg p-4">
                <!-- PDF Embed -->
                <div class="relative pb-2/3" style="padding-bottom: 75%;">
                    <embed 
                        src="{{ asset('maps/UB-MAP2.pdf') }}" 
                        type="application/pdf"
                        width="100%"
                        height="600px"
                        class="absolute inset-0 w-full h-full rounded-lg shadow-md"
                    >
                </div>
                
                <!-- Fallback for browsers that don't support PDF embedding -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600 mb-2">
                        <i class="fas fa-info-circle text-red-800 mr-1"></i>
                        If the map doesn't display, you can download it below.
                    </p>
                    <a href="{{ asset('maps/UB-MAP2.pdf') }}" 
                       class="inline-flex items-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition"
                       download>
                        <i class="fas fa-download mr-2"></i>
                        Download Campus Map (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
        <div class="bg-red-800 px-6 py-4">
            <h2 class="text-xl font-semibold text-white flex items-center">
                <i class="fas fa-search mr-2"></i>
                Find Offices & Services
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <input type="text" name="search" placeholder="Search offices, departments..." 
                       value="{{ request('search') }}"
                       class="flex-1 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                <select name="category" class="md:w-48 px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $cat)) }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg hover:bg-red-900 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Offices Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($offices as $office)
        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-200">
            <div class="h-2 bg-red-800"></div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $office->office_name }}</h3>
                <p class="text-gray-600 mb-4 text-sm">{{ $office->description }}</p>
                
                <div class="space-y-2 text-sm">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-red-800 mr-2 mt-1 w-4"></i>
                        <span>{{ $office->building }} {{ $office->room_number ? ' - ' . $office->room_number : '' }}</span>
                    </div>
                    
                    @if($office->phone)
                    <div class="flex items-center">
                        <i class="fas fa-phone text-red-800 mr-2 w-4"></i>
                        <span>{{ $office->phone }}</span>
                    </div>
                    @endif
                    
                    @if($office->email)
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-red-800 mr-2 w-4"></i>
                        <a href="mailto:{{ $office->email }}" class="text-red-800 hover:underline">{{ $office->email }}</a>
                    </div>
                    @endif
                    
                    @if($office->hours)
                    <div class="flex items-start">
                        <i class="fas fa-clock text-red-800 mr-2 mt-1 w-4"></i>
                        <span>{{ $office->hours }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                        {{ ucfirst(str_replace('_', ' ', $office->category)) }}
                    </span>
                </div>
                
                <!-- Map location link -->
                @if($office->building)
                <div class="mt-3 text-right">
                    <a href="#" onclick="alert('Building {{ $office->building }} is located near the center of campus. Check the map above!')" 
                       class="text-xs text-red-800 hover:underline">
                        <i class="fas fa-map-pin mr-1"></i>Locate on map
                    </a>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12 bg-white rounded-lg shadow">
            <div class="text-gray-400 text-5xl mb-4">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No offices found</h3>
            <p class="text-gray-500">Try adjusting your search criteria.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $offices->links() }}
    </div>
</div>

<script>
    // Optional: Add interactive map features
    document.addEventListener('DOMContentLoaded', function() {
        // You could add JavaScript to highlight buildings on the map
        console.log('Campus directory loaded with map');
    });
</script>
@endsection