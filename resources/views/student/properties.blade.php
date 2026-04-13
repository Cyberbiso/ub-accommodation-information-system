@extends('layouts.app')

@section('title', 'Verified Off-Campus Properties')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Verified Off-Campus Properties</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-red-700 font-semibold">Search Housing</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Browse verified properties near campus</h1>
                    <p class="text-gray-600 mt-2">Listings include distance to the University of Botswana, transport options, nearby amenities, and payment support.</p>
                </div>
                <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3 text-sm text-red-900">
                    Campus anchor: {{ $campus['name'] }}
                </div>
            </div>

            <form method="GET" action="{{ route('student.properties') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-4 mt-6">
                <input type="text" name="city" value="{{ request('city') }}" placeholder="City" class="border border-gray-300 rounded-lg px-4 py-3">
                <select name="type" class="border border-gray-300 rounded-lg px-4 py-3">
                    <option value="">All types</option>
                    @foreach($types as $type)
                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min price" class="border border-gray-300 rounded-lg px-4 py-3">
                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max price" class="border border-gray-300 rounded-lg px-4 py-3">
                <input type="number" step="0.1" name="max_distance" value="{{ request('max_distance') }}" placeholder="Max km to campus" class="border border-gray-300 rounded-lg px-4 py-3">
                <select name="sort" class="border border-gray-300 rounded-lg px-4 py-3">
                    <option value="">Newest</option>
                    <option value="nearest" {{ request('sort') === 'nearest' ? 'selected' : '' }}>Nearest to campus</option>
                    <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Lowest price</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Highest price</option>
                </select>
                <button type="submit" class="bg-red-800 text-white rounded-lg px-4 py-3 font-semibold">Apply Filters</button>
                <a href="{{ route('student.properties') }}" class="border border-gray-300 rounded-lg px-4 py-3 text-center font-semibold text-gray-700">Reset</a>
            </form>
        </div>

        @if($properties->count())
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-2xl shadow overflow-hidden">
                        <div class="h-48 bg-gradient-to-br from-red-900 via-red-800 to-amber-600 relative">
                            <div class="absolute inset-0 p-6 flex flex-col justify-between text-white">
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur">{{ ucfirst($property->type) }}</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-500/20 border border-green-200/40">
                                        {{ $property->available_units }} unit{{ $property->available_units > 1 ? 's' : '' }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-red-100">{{ $property->city }}</p>
                                    <h3 class="text-2xl font-bold mt-2">{{ $property->title }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">P{{ number_format($property->monthly_rent, 2) }}</p>
                                    <p class="text-sm text-gray-600">Monthly rent</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-900">{{ $property->campus_distance_label }}</p>
                                    <p class="text-sm text-gray-600">{{ $property->bedrooms }} bed • {{ $property->bathrooms }} bath</p>
                                </div>
                            </div>

                            <p class="text-gray-600 text-sm mt-4 line-clamp-3">{{ $property->description }}</p>

                            <div class="grid grid-cols-2 gap-3 mt-5 text-sm">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Available from</p>
                                    <p class="font-semibold text-gray-900">{{ $property->available_from?->format('d M Y') ?? 'Not set' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Lease</p>
                                    <p class="font-semibold text-gray-900">{{ $property->hasLeaseAgreement() ? 'Available' : 'Missing' }}</p>
                                </div>
                            </div>

                            @if($property->amenities)
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @foreach(collect($property->amenities)->take(4) as $amenity)
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-800">{{ $amenity }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $property->landlord->company_name ?? $property->landlord->name }}</span>
                                <a href="{{ route('student.properties.show', $property) }}" class="inline-flex items-center gap-2 bg-red-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-900 transition">
                                    View details
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div>
                {{ $properties->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow p-12 text-center">
                <i class="fas fa-map-marked-alt text-5xl text-gray-300"></i>
                <h3 class="text-2xl font-bold text-gray-900 mt-4">No matching properties found</h3>
                <p class="text-gray-600 mt-2">Try widening your price, city, or distance filters.</p>
            </div>
        @endif
    </div>
</div>
@endsection
