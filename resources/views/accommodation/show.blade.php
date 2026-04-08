@extends('layouts.public')

@section('title', $accommodation->name)

@section('content')
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $accommodation->name }}</h1>
                <p class="text-lg opacity-90">Block {{ $accommodation->block }} • Floor {{ $accommodation->floor }} • {{ ucfirst($accommodation->type) }}</p>
            </div>
            <a href="{{ route('accommodations.index') }}" class="text-white hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to On-Campus Rooms
            </a>
        </div>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900">Room facilities</h2>
                <div class="flex flex-wrap gap-2 mt-4">
                    @forelse($accommodation->facilities ?? [] as $facility)
                        <span class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 text-sm">{{ $facility }}</span>
                    @empty
                        <p class="text-gray-500">No facilities have been listed for this room yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="mt-8">
                @auth
                    @if(Auth::user()->isStudent())
                        <a href="{{ route('student.accommodations.show', $accommodation) }}" class="inline-flex items-center bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                            <i class="fas fa-file-signature mr-2"></i>Open Student Application Form
                        </a>
                    @else
                        <p class="text-gray-600">Only student accounts can apply for on-campus accommodation through the portal.</p>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                        <i class="fas fa-user-graduate mr-2"></i>Login as Student to Apply
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
