@extends('layouts.app')

@section('title', 'My Bookings')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">My Off-Campus Bookings</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Selected off-campus accommodation</h1>
                    <p class="text-gray-600 mt-1">Pay pending selections to confirm your booking.</p>
                </div>
                <a href="{{ route('student.properties') }}" class="border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Browse properties</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $booking->property->title }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $booking->booking_reference }} • {{ $booking->property->city }}</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4 text-sm">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Move in</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->move_in_date?->format('d M Y') ?? 'Not set' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Lease</p>
                                    <p class="font-semibold text-gray-900">{{ $booking->lease_months }} months</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Rent</p>
                                    <p class="font-semibold text-gray-900">P{{ number_format($booking->quoted_rent, 2) }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-500">Deposit</p>
                                    <p class="font-semibold text-gray-900">P{{ number_format($booking->deposit_amount, 2) }}</p>
                                </div>
                            </div>
                            @if($booking->special_requests)
                                <p class="text-sm text-gray-600 mt-4">{{ $booking->special_requests }}</p>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">
                            <p class="text-sm text-gray-500">Amount due</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">P{{ number_format($booking->total_amount, 2) }}</p>
                            @if($booking->payment && $booking->payment->status === 'pending')
                                @include('student._payment-form', [
                                    'payment' => $booking->payment,
                                    'submitLabel' => 'Pay and confirm',
                                    'formClass' => 'mt-5',
                                ])
                            @else
                                <div class="mt-5 text-sm text-green-700 font-semibold">Payment completed</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-house-user text-5xl text-gray-300"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mt-4">No property selections yet</h3>
                        <p class="text-gray-600 mt-2">Choose a verified off-campus property to start booking and payment.</p>
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
