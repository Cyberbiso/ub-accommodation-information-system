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
                    <p class="text-gray-600 mt-1">
                        {{ $selectedBooking ? 'Review and pay for the property you just selected.' : 'Pay pending selections to confirm your booking.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if($selectedBooking)
                        <a href="{{ route('student.bookings') }}" class="border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">View all bookings</a>
                    @endif
                    <a href="{{ route('student.properties') }}" class="border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Browse properties</a>
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex flex-col md:flex-row gap-5">
                                <div class="w-full md:w-56 shrink-0">
                                    <a href="{{ route('student.properties.show', $booking->property) }}" class="block overflow-hidden rounded-2xl border border-gray-200 bg-gray-100">
                                        @if(count($booking->property->photo_urls))
                                            <img src="{{ $booking->property->first_photo }}" alt="{{ $booking->property->title }}" class="h-40 w-full object-cover">
                                        @else
                                            <div class="h-40 w-full bg-gradient-to-br from-red-900 via-red-800 to-amber-600 flex items-center justify-center text-white">
                                                <i class="fas fa-house-user text-4xl"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="min-w-0 flex-1">
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
                                    <div class="mt-4 flex flex-wrap gap-3 text-sm">
                                        <a href="{{ route('student.properties.show', $booking->property) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            Open property
                                        </a>
                                    </div>
                                    <div class="mt-4 rounded-xl border border-gray-200 p-4">
                                        <p class="text-sm font-semibold text-gray-900">Lease workflow</p>
                                        <p class="text-sm text-gray-600 mt-2">{{ $booking->property->available_from_label }}</p>
                                        @if($booking->property->hasLeaseAgreement())
                                            <div class="mt-3 flex flex-wrap gap-3 text-sm">
                                                <a href="{{ route('documents.property-lease.show', $booking->property) }}" target="_blank" class="text-red-800 hover:underline">Open landlord lease</a>
                                                <a href="{{ route('documents.property-lease.show', ['property' => $booking->property, 'download' => 1]) }}" class="text-red-800 hover:underline">Download lease</a>
                                                @if($booking->hasSignedLease())
                                                    <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-red-800 hover:underline">Open submitted signed lease</a>
                                                    <a href="{{ route('documents.signed-lease.show', ['booking' => $booking, 'download' => 1]) }}" class="text-red-800 hover:underline">Download submitted signed lease</a>
                                                @endif
                                            </div>

                                            <form method="POST" action="{{ route('student.bookings.signed-lease.upload', $booking) }}" enctype="multipart/form-data" class="mt-4 space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload signed lease</label>
                                                    <input type="file" name="signed_lease" class="block w-full text-sm text-gray-700" required>
                                                </div>
                                                <button type="submit" class="inline-flex items-center rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition">
                                                    {{ $booking->hasSignedLease() ? 'Replace signed lease' : 'Submit signed lease' }}
                                                </button>
                                                @if($booking->signed_lease_submitted_at)
                                                    <p class="text-xs text-gray-500">Last submitted {{ $booking->signed_lease_submitted_at->diffForHumans() }}</p>
                                                @endif
                                            </form>
                                        @else
                                            <p class="text-sm text-amber-700 mt-2">The landlord has not uploaded a lease agreement for this property yet.</p>
                                        @endif
                                    </div>
                                    @if($booking->special_requests)
                                        <p class="text-sm text-gray-600 mt-4">{{ $booking->special_requests }}</p>
                                    @endif
                                </div>
                            </div>
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
                        <h3 class="text-2xl font-bold text-gray-900 mt-4">{{ $hasBookingFilter ? 'That booking could not be found' : 'No property selections yet' }}</h3>
                        <p class="text-gray-600 mt-2">
                            {{ $hasBookingFilter ? 'Try returning to your bookings list or choose another property.' : 'Choose a verified off-campus property to start booking and payment.' }}
                        </p>
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
