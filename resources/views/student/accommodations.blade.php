@extends('layouts.app')

@section('title', 'Browse On-Campus Accommodations')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Browse On-Campus Accommodations
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Available Accommodations</h3>
                
                @if(isset($accommodations) && $accommodations->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($accommodations as $accommodation)
                            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                                <h4 class="font-bold text-lg">{{ $accommodation->name }}</h4>
                                <p class="text-sm text-gray-600">Type: {{ ucfirst($accommodation->type) }}</p>
                                <p class="text-sm text-gray-600">Rent: P{{ number_format($accommodation->monthly_rent, 2) }}/month</p>
                                <p class="text-sm text-gray-600">Capacity: {{ $accommodation->current_occupancy }}/{{ $accommodation->capacity }}</p>
                                <a href="#" class="mt-3 inline-block bg-red-800 text-white px-4 py-2 rounded hover:bg-red-900">View Details</a>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $accommodations->links() }}
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">No accommodations available at the moment.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection