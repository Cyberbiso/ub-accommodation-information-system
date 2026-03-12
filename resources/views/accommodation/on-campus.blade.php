@extends('layouts.public')

@section('title', 'On-Campus Accommodations')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl font-bold mb-4">On-Campus Accommodations</h1>
        <p class="text-xl mb-8 max-w-3xl">
            Browse available university housing options. Apply now to secure your spot!
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
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
                       placeholder="e.g., 500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Block</label>
                <select name="block" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                    <option value="">All Blocks</option>
                    @foreach($blocks as $block)
                        <option value="{{ $block }}" {{ request('block') == $block ? 'selected' : '' }}>
                            Block {{ $block }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Accommodations Grid -->
    @if($accommodations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($accommodations as $accommodation)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition border border-gray-200">
                    <div class="h-2 bg-red-800"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $accommodation->name }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-tag text-red-800 w-5"></i>
                                <span>P{{ number_format($accommodation->monthly_rent, 2) }}/month</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-users text-red-800 w-5"></i>
                                <span>{{ ucfirst($accommodation->type) }} • Capacity: {{ $accommodation->capacity }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-800 w-5"></i>
                                <span>Block {{ $accommodation->block }}, Floor {{ $accommodation->floor }}</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-bed text-red-800 w-5"></i>
                                <span>{{ $accommodation->availableSpaces() }} space(s) available</span>
                            </div>
                        </div>
                        
                        @if($accommodation->facilities)
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Facilities:</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(json_decode($accommodation->facilities) ?? [] as $facility)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">{{ $facility }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @auth
                            @if(Auth::user()->isStudent())
                                <a href="{{ route('student.accommodations.apply.form') }}" 
                                   class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                    Apply Now
                                </a>
                            @else
                                <a href="{{ route('login') }}" 
                                   class="block w-full text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-lg cursor-not-allowed">
                                    Login as Student to Apply
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                Login to Apply
                            </a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $accommodations->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <div class="text-gray-400 text-6xl mb-4">
                <i class="fas fa-home"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">No Accommodations Available</h3>
            <p class="text-gray-500">Check back later for available rooms.</p>
        </div>
    @endif
</div>
@endsection