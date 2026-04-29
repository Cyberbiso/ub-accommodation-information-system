@extends('layouts.app')

@section('title', 'My Properties')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">My Properties</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Your accommodation listings</h1>
                    <p class="text-gray-600 mt-1">Keep location and transport details accurate for students.</p>
                </div>
                <a href="{{ route('landlord.properties.create') }}" class="bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">New property</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($properties as $property)
                    <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $property->title }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $property->available_units }} unit{{ $property->available_units > 1 ? 's' : '' }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->review_status === 'approved' ? 'bg-green-100 text-green-800' : ($property->review_status === 'changes_requested' ? 'bg-blue-100 text-blue-800' : ($property->review_status === 'rejected' || $property->review_status === 'removed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $property->review_status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $property->city }} • P{{ number_format($property->monthly_rent, 2) }}/month • {{ $property->campus_distance_label }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $property->available_from_label }} • Lease {{ $property->hasLeaseAgreement() ? 'uploaded' : 'missing' }}</p>
                            @if($property->review_notes)
                                <p class="text-sm text-gray-700 mt-2">{{ $property->review_notes }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('landlord.properties.edit', $property) }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Edit</a>
                            <form method="POST" action="{{ route('landlord.properties.destroy', $property) }}" onsubmit="return confirm('Remove this property listing?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border border-red-200 text-red-700 px-4 py-2 rounded-lg font-semibold hover:bg-red-50 transition">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No property listings yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $properties->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
