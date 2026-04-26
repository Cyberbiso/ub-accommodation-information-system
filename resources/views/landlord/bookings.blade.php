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
                <p class="text-gray-600 mt-1 text-sm">Review and manage booking requests from students.</p>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                    @php
                        $statusColors = [
                            'pending_landlord_review'          => 'bg-yellow-100 text-yellow-800',
                            'approved_awaiting_lease'          => 'bg-blue-100 text-blue-800',
                            'lease_pending_landlord_approval'  => 'bg-purple-100 text-purple-800',
                            'approved_awaiting_payment'        => 'bg-indigo-100 text-indigo-800',
                            'confirmed'                        => 'bg-green-100 text-green-800',
                            'rejected'                         => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending_landlord_review'          => 'Awaiting Your Review',
                            'approved_awaiting_lease'          => 'Awaiting Signed Lease',
                            'lease_pending_landlord_approval'  => 'Signed Lease — Review Required',
                            'approved_awaiting_payment'        => 'Awaiting Payment',
                            'confirmed'                        => 'Confirmed',
                            'rejected'                         => 'Rejected',
                        ];
                        $badgeClass  = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                        $statusLabel = $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status));
                    @endphp

                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                            {{-- Booking info --}}
                            <div class="flex-1">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <h3 class="text-xl font-bold text-gray-900">{{ $booking->property->title }}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    {{ $booking->student->name }} &bull; {{ $booking->booking_reference }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Move in {{ $booking->move_in_date?->format('d M Y') ?? 'Not set' }} &bull;
                                    {{ $booking->lease_months }} months &bull;
                                    P{{ number_format($booking->total_amount, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 mt-1">
                                    Payment: {{ ucfirst($booking->payment?->status ?? 'pending') }}
                                </p>

                                {{-- Lease documents --}}
                                <div class="mt-3 flex flex-wrap gap-2 text-sm">
                                    @if($booking->hasSignedLease())
                                        <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-red-800 hover:underline">View signed lease</a>
                                        <a href="{{ route('documents.signed-lease.show', ['booking' => $booking, 'download' => 1]) }}" class="text-red-800 hover:underline">Download signed lease</a>
                                    @elseif(!$booking->isPendingLandlordReview() && !$booking->isRejected())
                                        <span class="text-amber-700">Awaiting student's signed lease</span>
                                    @endif
                                </div>

                                @if($booking->isRejected() && $booking->landlord_rejection_note)
                                    <p class="text-sm text-gray-500 mt-2">Rejection note: {{ $booking->landlord_rejection_note }}</p>
                                @endif
                            </div>

                            {{-- Approve / Reject booking --}}
                            @if($booking->isPendingLandlordReview())
                                <div class="flex flex-col gap-3 lg:items-end lg:min-w-64">
                                    <form method="POST" action="{{ route('landlord.bookings.approve', $booking) }}">
                                        @csrf
                                        <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600 transition">
                                            Approve booking
                                        </button>
                                    </form>

                                    <details class="w-full lg:w-64">
                                        <summary class="cursor-pointer text-sm text-red-700 font-semibold hover:underline">Decline booking</summary>
                                        <form method="POST" action="{{ route('landlord.bookings.reject', $booking) }}" class="mt-3 space-y-2">
                                            @csrf
                                            <textarea
                                                name="rejection_note"
                                                rows="3"
                                                placeholder="Optional reason for declining…"
                                                class="w-full rounded-lg border border-gray-300 text-sm p-2 focus:outline-none focus:ring-2 focus:ring-red-300"
                                            ></textarea>
                                            <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition">
                                                Confirm decline
                                            </button>
                                        </form>
                                    </details>
                                </div>

                            {{-- Approve / Decline signed lease --}}
                            @elseif($booking->isLeasePendingLandlordApproval())
                                <div class="flex flex-col gap-3 lg:items-end lg:min-w-64">
                                    @if($booking->hasSignedLease())
                                        <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-sm text-red-800 hover:underline">View signed lease</a>
                                    @endif

                                    <form method="POST" action="{{ route('landlord.bookings.lease.approve', $booking) }}">
                                        @csrf
                                        <button type="submit" class="w-full lg:w-auto inline-flex items-center justify-center rounded-lg bg-green-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-600 transition">
                                            Approve signed lease
                                        </button>
                                    </form>

                                    <details class="w-full lg:w-64">
                                        <summary class="cursor-pointer text-sm text-red-700 font-semibold hover:underline">Decline signed lease</summary>
                                        <form method="POST" action="{{ route('landlord.bookings.lease.reject', $booking) }}" class="mt-3 space-y-2">
                                            @csrf
                                            <textarea
                                                name="rejection_note"
                                                rows="3"
                                                placeholder="Reason for declining (student will be asked to re-sign)…"
                                                class="w-full rounded-lg border border-gray-300 text-sm p-2 focus:outline-none focus:ring-2 focus:ring-red-300"
                                            ></textarea>
                                            <button type="submit" class="inline-flex items-center rounded-lg border border-red-300 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition">
                                                Decline &amp; request re-sign
                                            </button>
                                        </form>
                                    </details>
                                </div>
                            @endif
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
