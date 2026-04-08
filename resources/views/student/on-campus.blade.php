@extends('layouts.app')

@section('title', 'On-Campus Accommodations')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        On-Campus Accommodations
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white rounded-lg shadow-lg mb-6 p-6">
            <h1 class="text-2xl font-bold mb-2">On-Campus Housing</h1>
            <p class="opacity-90">Browse available university-managed accommodation options.</p>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-lg mb-6 p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
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
                           placeholder="e.g., 300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Max Price (P)</label>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800"
                           placeholder="e.g., 600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Block</label>
                    <select name="block" class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                        <option value="">All Blocks</option>
                        @foreach($blocks as $block)
                            <option value="{{ $block }}" {{ request('block') == $block ? 'selected' : '' }}>
                                Block {{ $block }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit" class="bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($accommodations as $accommodation)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="h-2 bg-red-800"></div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2">{{ $accommodation->name }}</h3>
                        
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-tag text-red-800 w-5"></i>
                                <span>P{{ number_format($accommodation->monthly_rent, 2) }}/month</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-red-800 w-5"></i>
                                <span>{{ ucfirst($accommodation->type) }} • Capacity: {{ $accommodation->capacity }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-map-marker-alt text-red-800 w-5"></i>
                                <span>Block {{ $accommodation->block }}, Floor {{ $accommodation->floor }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-bed text-red-800 w-5"></i>
                                <span>{{ $accommodation->availableSpaces() }} space(s) available</span>
                            </div>
                        </div>

                        @if($accommodation->facilities)
                            <div class="mb-4">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($accommodation->facilities ?? [] as $facility)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">{{ $facility }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('student.accommodations.show', $accommodation) }}" 
                           class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-lg">
                    <i class="fas fa-home text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No Accommodations Found</h3>
                    <p class="text-gray-500">Try adjusting your filters.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $accommodations->links() }}
        </div>
    </div>
</div>
@endsection
