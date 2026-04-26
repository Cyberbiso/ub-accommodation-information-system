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
                        {{ $selectedBooking ? 'Track your booking progress below.' : 'Track all your off-campus booking requests.' }}
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
                    @php
                        $statusColors = [
                            'pending_landlord_review'   => 'bg-yellow-100 text-yellow-800',
                            'approved_awaiting_lease'   => 'bg-blue-100 text-blue-800',
                            'approved_awaiting_payment' => 'bg-indigo-100 text-indigo-800',
                            'confirmed'                 => 'bg-green-100 text-green-800',
                            'rejected'                  => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'pending_landlord_review'   => 'Awaiting Landlord Review',
                            'approved_awaiting_lease'   => 'Approved — Sign Lease',
                            'approved_awaiting_payment' => 'Lease Signed — Proceed to Payment',
                            'confirmed'                 => 'Confirmed',
                            'rejected'                  => 'Rejected',
                        ];
                        $badgeClass = $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-800';
                        $statusLabel = $statusLabels[$booking->status] ?? ucfirst(str_replace('_', ' ', $booking->status));
                    @endphp

                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        {{-- Left: property info --}}
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
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                            {{ $statusLabel }}
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

                                    <div class="mt-4">
                                        <a href="{{ route('student.properties.show', $booking->property) }}" class="inline-flex items-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                            Open property
                                        </a>
                                    </div>

                                    {{-- ── Step-based action panel ── --}}
                                    <div class="mt-4 rounded-xl border border-gray-200 p-4 space-y-3">

                                        {{-- STEP 1: Awaiting landlord review --}}
                                        @if($booking->isPendingLandlordReview())
                                            <p class="text-sm font-semibold text-gray-900">Step 1 of 3 — Awaiting landlord review</p>
                                            <p class="text-sm text-gray-600">Your request has been sent to the landlord. You will be notified once they review it.</p>

                                        {{-- STEP 2: Approved — sign the lease --}}
                                        @elseif($booking->isApprovedAwaitingLease())
                                            <p class="text-sm font-semibold text-gray-900">Step 2 of 4 — Read &amp; sign the lease</p>
                                            @if($booking->landlord_rejection_note)
                                                <p class="text-sm text-red-700">Your previous signature was declined: {{ $booking->landlord_rejection_note }}. Please sign again.</p>
                                            @else
                                                <p class="text-sm text-green-700">Your booking was approved! Review the lease agreement and sign below.</p>
                                            @endif

                                            @if($booking->property->hasLeaseAgreement())
                                                <a href="{{ route('documents.property-lease.show', $booking->property) }}" target="_blank" class="inline-flex items-center gap-1 text-sm text-red-800 hover:underline">
                                                    <i class="fas fa-file-pdf"></i> Open lease agreement
                                                </a>

                                                <form method="POST" action="{{ route('student.bookings.signed-lease.upload', $booking) }}" class="space-y-3 pt-2" data-sign-lease="{{ $booking->id }}">
                                                    @csrf
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-700 mb-2">Sign below</p>
                                                        <div class="border-2 border-gray-300 rounded-lg overflow-hidden bg-white">
                                                            <canvas id="sig-canvas-{{ $booking->id }}" width="600" height="150" class="w-full cursor-crosshair" style="touch-action: none; display: block;"></canvas>
                                                        </div>
                                                        <button type="button" class="mt-1 text-xs text-gray-500 hover:text-red-800 underline" onclick="clearSignature('{{ $booking->id }}')">Clear signature</button>
                                                        <input type="hidden" name="signature" id="sig-data-{{ $booking->id }}">
                                                        <p class="text-xs text-gray-400 mt-1">Draw your signature using a mouse or finger. Make sure it is visible before submitting.</p>
                                                    </div>
                                                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-800 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition">
                                                        Submit signed lease
                                                    </button>
                                                </form>
                                            @else
                                                <p class="text-sm text-amber-700">The landlord has not uploaded a lease agreement yet. Contact them to request it.</p>
                                            @endif

                                        {{-- STEP 3: Signed lease awaiting landlord approval --}}
                                        @elseif($booking->isLeasePendingLandlordApproval())
                                            <p class="text-sm font-semibold text-gray-900">Step 3 of 4 — Awaiting lease approval</p>
                                            <p class="text-sm text-blue-700">Your signed lease has been submitted. The landlord will review and approve or decline it shortly.</p>
                                            @if($booking->hasSignedLease())
                                                <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-sm text-red-800 hover:underline">View submitted signature</a>
                                            @endif

                                        {{-- STEP 4: Lease approved — proceed to payment --}}
                                        @elseif($booking->isApprovedAwaitingPayment())
                                            <p class="text-sm font-semibold text-gray-900">Step 4 of 4 — Complete payment</p>
                                            <p class="text-sm text-green-700">Lease approved. Complete your payment below to confirm your booking.</p>

                                            @if($booking->hasSignedLease())
                                                <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-sm text-red-800 hover:underline">View submitted signature</a>
                                            @endif

                                        {{-- CONFIRMED --}}
                                        @elseif($booking->isConfirmed())
                                            <p class="text-sm font-semibold text-green-800">Booking confirmed</p>
                                            <p class="text-sm text-gray-600">Payment received on {{ $booking->paid_at?->format('d M Y') }}. Your accommodation is secured.</p>
                                            @if($booking->hasSignedLease())
                                                <div class="flex flex-wrap gap-3 text-sm">
                                                    <a href="{{ route('documents.signed-lease.show', $booking) }}" target="_blank" class="text-red-800 hover:underline">View lease</a>
                                                    <a href="{{ route('documents.signed-lease.show', ['booking' => $booking, 'download' => 1]) }}" class="text-red-800 hover:underline">Download lease</a>
                                                </div>
                                            @endif

                                        {{-- REJECTED --}}
                                        @elseif($booking->isRejected())
                                            <p class="text-sm font-semibold text-red-700">Booking request declined</p>
                                            @if($booking->landlord_rejection_note)
                                                <p class="text-sm text-gray-600">Reason: {{ $booking->landlord_rejection_note }}</p>
                                            @else
                                                <p class="text-sm text-gray-600">The landlord did not approve this booking request. You may browse other properties.</p>
                                            @endif
                                        @endif

                                    </div>

                                    @if($booking->special_requests)
                                        <p class="text-sm text-gray-600 mt-3"><span class="font-medium">Special requests:</span> {{ $booking->special_requests }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Right: payment panel --}}
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <p class="text-sm text-gray-500">Amount due</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">P{{ number_format($booking->total_amount, 2) }}</p>

                            @if($booking->isApprovedAwaitingPayment() && $booking->payment && $booking->payment->status === 'pending')
                                @include('student._payment-form', [
                                    'payment' => $booking->payment,
                                    'submitLabel' => 'Pay and confirm',
                                    'formClass' => 'mt-5',
                                ])
                            @elseif($booking->isConfirmed())
                                <div class="mt-5 text-sm text-green-700 font-semibold">Payment completed</div>
                            @elseif($booking->isRejected())
                                <div class="mt-5 text-sm text-red-600">No payment required.</div>
                            @else
                                <div class="mt-5 text-sm text-gray-500">Payment will be available once your lease is signed.</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-house-user text-5xl text-gray-300"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mt-4">{{ $hasBookingFilter ? 'That booking could not be found' : 'No property selections yet' }}</h3>
                        <p class="text-gray-600 mt-2">
                            {{ $hasBookingFilter ? 'Try returning to your bookings list or choose another property.' : 'Choose a verified off-campus property to start the booking process.' }}
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

@push('scripts')
<script>
function initSignaturePad(bookingId) {
    const canvas = document.getElementById('sig-canvas-' + bookingId);
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    ctx.strokeStyle = '#111827';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    let drawing = false;

    function getPos(e) {
        const rect = canvas.getBoundingClientRect();
        const scaleX = canvas.width / rect.width;
        const scaleY = canvas.height / rect.height;
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;
        return { x: (clientX - rect.left) * scaleX, y: (clientY - rect.top) * scaleY };
    }

    canvas.addEventListener('mousedown',  (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
    canvas.addEventListener('mousemove',  (e) => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
    canvas.addEventListener('mouseup',    () => { drawing = false; });
    canvas.addEventListener('mouseleave', () => { drawing = false; });
    canvas.addEventListener('touchstart', (e) => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, { passive: false });
    canvas.addEventListener('touchmove',  (e) => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }, { passive: false });
    canvas.addEventListener('touchend',   () => { drawing = false; });

    const form = canvas.closest('form[data-sign-lease]');
    if (form) {
        form.addEventListener('submit', function(e) {
            const blank = document.createElement('canvas');
            blank.width = canvas.width;
            blank.height = canvas.height;
            if (canvas.toDataURL() === blank.toDataURL()) {
                e.preventDefault();
                alert('Please draw your signature before submitting.');
                return;
            }
            document.getElementById('sig-data-' + bookingId).value = canvas.toDataURL('image/png');
        });
    }
}

function clearSignature(bookingId) {
    const canvas = document.getElementById('sig-canvas-' + bookingId);
    if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form[data-sign-lease]').forEach(function(form) {
        initSignaturePad(form.dataset.signLease);
    });
});
</script>
@endpush
