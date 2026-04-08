@extends('layouts.app')

@section('title', $accommodation->name)

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Accommodation Details</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow p-8">
            <h1 class="text-3xl font-bold text-gray-900">{{ $accommodation->name }}</h1>
            <p class="text-gray-600 mt-2">Block {{ $accommodation->block }} • Floor {{ $accommodation->floor }} • {{ ucfirst($accommodation->type) }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Monthly Rent</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">P{{ number_format($accommodation->monthly_rent, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Capacity</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $accommodation->capacity }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Available Spaces</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $accommodation->availableSpaces() }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('student.accommodations.apply', $accommodation) }}" class="space-y-4 mt-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred move-in date</label>
                        <input type="date" name="preferred_move_in_date" min="{{ now()->addDay()->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Duration (months)</label>
                        <input type="number" name="duration_months" min="6" max="36" value="12" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special requirements</label>
                    <textarea name="special_requirements" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3"></textarea>
                </div>
                <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Apply for this room</button>
            </form>
        </div>
    </div>
</div>
@endsection
