@extends('layouts.app')

@section('title', 'Bookings')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Bookings</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">Student bookings</h1>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $booking->property->title }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $booking->student->name }} • {{ $booking->booking_reference }}</p>
                            <p class="text-sm text-gray-600 mt-1">Move in {{ $booking->move_in_date?->format('d M Y') ?? 'Not set' }} • P{{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                        <div class="text-sm text-gray-600">
                            Payment: {{ ucfirst($booking->payment->status ?? 'pending') }}
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No bookings yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
