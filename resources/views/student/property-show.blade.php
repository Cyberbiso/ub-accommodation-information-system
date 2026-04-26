@extends('layouts.app')

@section('title', $property->title)

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Property Details</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <a href="{{ route('student.properties') }}" class="text-red-800 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to verified properties
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="bg-gradient-to-br from-red-950 via-red-900 to-amber-700 text-white p-8">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.2em] text-white/70">Verified Listing</p>
                        <h1 class="text-4xl font-bold mt-3">{{ $property->title }}</h1>
                        <p class="text-white/80 mt-3 max-w-3xl">{{ $property->description }}</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur rounded-2xl px-6 py-5 min-w-[240px]">
                        <p class="text-sm text-white/80">Monthly rent</p>
                        <p class="text-3xl font-bold mt-2">P{{ number_format($property->monthly_rent, 2) }}</p>
                        <p class="text-sm text-white/80 mt-2">Deposit: P{{ number_format($property->deposit_amount ?? $property->monthly_rent, 2) }}</p>
                        <p class="text-sm text-white/80 mt-2">{{ $property->campus_distance_label }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 xl:grid-cols-3 gap-8">
                <div class="xl:col-span-2 space-y-6">
                    @if($property->photo_urls)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <img src="{{ $property->first_photo }}" alt="{{ $property->title }}" class="md:col-span-2 w-full h-80 object-cover rounded-2xl border border-gray-200">
                            <div class="grid grid-cols-2 md:grid-cols-1 gap-4">
                                @foreach(collect($property->photo_urls)->slice(1, 4) as $photo)
                                    <img src="{{ $photo }}" alt="{{ $property->title }}" class="w-full h-[118px] object-cover rounded-2xl border border-gray-200">
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-sm text-gray-500">Bedrooms</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $property->bedrooms }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-sm text-gray-500">Bathrooms</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $property->bathrooms }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-sm text-gray-500">Units left</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2">{{ $property->available_units }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-2xl p-4">
                            <p class="text-sm text-gray-500">Type</p>
                            <p class="text-2xl font-bold text-gray-900 mt-2 capitalize">{{ $property->type }}</p>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Availability</h3>
                        <p class="text-gray-700 mt-2">{{ $property->available_from_label }}</p>
                        <p class="text-sm text-gray-600 mt-2">Bookings can only use move-in dates from {{ \Illuminate\Support\Carbon::parse($property->earliest_move_in_date)->format('d M Y') }} onward.</p>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Location and access</h3>
                        <p class="text-gray-600 mt-2">{{ $property->full_address }}</p>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-semibold text-gray-900">Transport routes</h4>
                                <div class="mt-3 space-y-2">
                                    @forelse($property->transport_routes ?? [] as $route)
                                        <div class="bg-white rounded-xl px-4 py-3 border border-gray-200 text-sm text-gray-700">{{ $route }}</div>
                                    @empty
                                        <p class="text-sm text-gray-500">Transport routes have not been added for this property yet.</p>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Nearby amenities</h4>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @forelse($property->nearby_amenities ?? [] as $amenity)
                                        <span class="px-3 py-2 rounded-full bg-white border border-gray-200 text-sm text-gray-700">{{ $amenity }}</span>
                                    @empty
                                        <p class="text-sm text-gray-500">No nearby amenities added yet.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Interactive map</h3>
                                <p class="text-gray-600 mt-2">Preview the property on Google Maps, switch to the campus route, then launch GPS navigation from your current location.</p>
                            </div>
                            @if($property->hasCoordinates())
                                @php
                                    $propertyPreviewUrl = 'https://maps.google.com/maps?q=' . $property->latitude . ',' . $property->longitude . '&z=15&output=embed';
                                    $campusRoutePreviewUrl = 'https://maps.google.com/maps?saddr=' . $campus['latitude'] . ',' . $campus['longitude'] . '&daddr=' . $property->latitude . ',' . $property->longitude . '&output=embed';
                                @endphp
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" data-preview="{{ $propertyPreviewUrl }}" class="map-preview-toggle px-4 py-2 rounded-lg bg-red-800 text-sm font-semibold text-white">Property preview</button>
                                    <button type="button" data-preview="{{ $campusRoutePreviewUrl }}" class="map-preview-toggle px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700">Preview route from campus</button>
                                </div>
                            @endif
                        </div>

                        @if($property->hasCoordinates())
                            <div class="mt-6 rounded-2xl border border-gray-200 overflow-hidden bg-gray-100">
                                <iframe
                                    id="googleMapPreview"
                                    src="{{ $propertyPreviewUrl }}"
                                    class="w-full h-[420px]"
                                    style="border:0;"
                                    loading="lazy"
                                    allowfullscreen
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <p class="mt-3 text-sm text-gray-500">Use the buttons above to switch between the property pin and the route from {{ $campus['name'] }}.</p>
                            <div class="mt-4 flex flex-wrap gap-3">
                                <a href="{{ $property->navigation_url }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                                    <i class="fas fa-route"></i>Open in Google Maps
                                </a>
                                <a href="{{ $property->campus_route_url }}" target="_blank" rel="noreferrer" class="inline-flex items-center gap-2 border border-red-800 text-red-800 px-4 py-3 rounded-lg font-semibold hover:bg-red-50 transition">
                                    <i class="fas fa-school"></i>Open route from campus
                                </a>
                                <button type="button" id="gpsNavigateBtn" class="inline-flex items-center gap-2 border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                    <i class="fas fa-location-arrow"></i>Navigate from my location
                                </button>
                            </div>
                            <p class="mt-3 text-sm text-gray-500">
                                Campus route origin: {{ $campus['latitude'] }}, {{ $campus['longitude'] }}.
                                Property destination: {{ $property->latitude }}, {{ $property->longitude }}.
                            </p>
                        @else
                            <div class="mt-6 rounded-2xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                                GPS coordinates have not been added to this listing yet.
                            </div>
                        @endif

                        @if($property->navigation_notes)
                            <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                <span class="font-semibold text-gray-900">Navigation notes:</span>
                                {{ $property->navigation_notes }}
                            </div>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Property features</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @forelse($property->amenities ?? [] as $amenity)
                                <span class="px-4 py-2 rounded-full bg-white border border-gray-200 text-sm text-gray-700">{{ $amenity }}</span>
                            @empty
                                <p class="text-sm text-gray-500">No extra features added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Landlord</h3>
                        <p class="text-gray-600 mt-2">{{ $landlord->company_name ?? $landlord->name }}</p>
                        <div class="mt-4 space-y-2 text-sm text-gray-700">
                            <p><i class="fas fa-phone text-red-800 mr-2"></i>{{ $landlord->phone ?? 'Contact available after request' }}</p>
                            <p><i class="fas fa-envelope text-red-800 mr-2"></i>{{ $landlord->email }}</p>
                            <p><i class="fas fa-shield-check text-red-800 mr-2"></i>Verification complete</p>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Book this accommodation</h3>
                        @if($existingBooking)
                            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-900">
                                Existing booking: {{ $existingBooking->booking_reference }} ({{ ucfirst(str_replace('_', ' ', $existingBooking->status)) }})
                            </div>
                        @endif
                        @if($property->hasLeaseAgreement())
                            <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                <p class="font-semibold text-gray-900">Lease agreement</p>
                                <p class="mt-2">Read the lease before booking. Once your booking is approved you will sign it digitally from your bookings page.</p>
                                <a href="{{ route('documents.property-lease.show', $property) }}" target="_blank" class="inline-flex items-center gap-2 mt-3 text-red-800 hover:underline">
                                    <i class="fas fa-file-pdf"></i> Read lease agreement
                                </a>
                            </div>
                        @endif
                        @if($existingBooking)
                            <div class="mt-4 space-y-3">
                                @if($existingBooking->isPendingLandlordReview())
                                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-900">
                                        Your booking request is awaiting landlord review.
                                    </div>
                                    <a href="{{ route('student.bookings', ['booking' => $existingBooking->id]) }}" class="block w-full text-center border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                        Open this booking
                                    </a>
                                @elseif($existingBooking->isApprovedAwaitingLease())
                                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-900">
                                        Your booking was approved. Download the lease, sign it, and upload your signed copy.
                                    </div>
                                    <a href="{{ route('student.bookings', ['booking' => $existingBooking->id]) }}" class="block w-full text-center bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                                        Upload signed lease
                                    </a>
                                @elseif($existingBooking->isLeasePendingLandlordApproval())
                                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-4 text-sm text-purple-900">
                                        Your signed lease has been submitted and is awaiting landlord approval.
                                    </div>
                                    <a href="{{ route('student.bookings', ['booking' => $existingBooking->id]) }}" class="block w-full text-center border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                        Open this booking
                                    </a>
                                @elseif($existingBooking->isApprovedAwaitingPayment())
                                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-900">
                                        Lease approved. Complete your payment to confirm this booking.
                                    </div>
                                    <a href="{{ route('student.payments') }}" class="block w-full text-center bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                                        Continue payment for this booking
                                    </a>
                                @else
                                    <div class="bg-green-50 border border-green-100 rounded-xl p-4 text-sm text-green-900">
                                        This property is already confirmed in your bookings.
                                    </div>
                                    <a href="{{ route('student.bookings', ['booking' => $existingBooking->id]) }}" class="block w-full text-center border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">
                                        Open this booking
                                    </a>
                                @endif
                            </div>
                        @else
                            <form method="POST" action="{{ route('student.properties.book', $property) }}" class="space-y-4 mt-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred move-in date</label>
                                    <input type="date" name="move_in_date" min="{{ $property->earliest_move_in_date }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Lease months</label>
                                        <input type="number" name="lease_months" value="12" min="3" max="24" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Occupants</label>
                                        <input type="number" name="occupants" value="1" min="1" max="8" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Special requests</label>
                                    <textarea name="special_requests" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Accessibility, furnishing, or move-in notes"></textarea>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    Total payable now: <span class="font-bold text-gray-900">P{{ number_format($property->monthly_rent + ($property->deposit_amount ?? $property->monthly_rent), 2) }}</span>
                                </div>
                                <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                                    Select and pay for this property
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Arrange a viewing</h3>
                        @if($existingRequest)
                            <div class="mt-4 bg-amber-50 border border-amber-100 rounded-xl p-4 text-sm text-amber-900">
                                Latest request status: {{ ucfirst($existingRequest->status) }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('student.viewing-request', $property) }}" class="space-y-4 mt-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Preferred viewing date</label>
                                <input type="date" name="preferred_date" min="{{ now()->addDay()->toDateString() }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Message to landlord</label>
                                <textarea name="message" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Any timing or access notes"></textarea>
                            </div>
                            <button type="submit" class="w-full border border-red-800 text-red-800 px-4 py-3 rounded-lg font-semibold hover:bg-red-50 transition">
                                Send viewing request
                            </button>
                        </form>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Ask the landlord a question</h3>
                        @if($existingEnquiry)
                            <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-900">
                                Latest enquiry: {{ $existingEnquiry->subject }} ({{ ucfirst($existingEnquiry->status) }})
                            </div>
                        @endif
                        <form method="POST" action="{{ route('student.properties.enquiries.store', $property) }}" class="space-y-4 mt-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" name="subject" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Ask about availability, utilities, or lease terms" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                                <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Type your question for the landlord" required></textarea>
                            </div>
                            <button type="submit" class="w-full border border-red-800 text-red-800 px-4 py-3 rounded-lg font-semibold hover:bg-red-50 transition">
                                Send enquiry
                            </button>
                        </form>
                    </div>

                    @if($similarProperties->count())
                        <div class="bg-white border border-gray-200 rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-gray-900">Similar properties</h3>
                            <div class="space-y-3 mt-4">
                                @foreach($similarProperties as $similar)
                                    <a href="{{ route('student.properties.show', $similar) }}" class="block rounded-xl border border-gray-200 p-4 hover:border-red-300 transition">
                                        <p class="font-semibold text-gray-900">{{ $similar->title }}</p>
                                        <p class="text-sm text-gray-600 mt-1">P{{ number_format($similar->monthly_rent, 2) }} • {{ $similar->campus_distance_label }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($property->hasCoordinates())
<script>
    const propertyPoint = { lat: {{ $property->latitude }}, lng: {{ $property->longitude }} };
    const previewFrame = document.getElementById('googleMapPreview');
    const previewButtons = document.querySelectorAll('.map-preview-toggle');
    const gpsNavigateBtn = document.getElementById('gpsNavigateBtn');

    previewButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (previewFrame) {
                previewFrame.src = button.dataset.preview;
            }

            previewButtons.forEach((item) => {
                item.classList.remove('bg-red-800', 'text-white');
                item.classList.add('border', 'border-gray-300', 'text-gray-700');
            });

            button.classList.remove('border', 'border-gray-300', 'text-gray-700');
            button.classList.add('bg-red-800', 'text-white');
        });
    });

    gpsNavigateBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            window.open(@json($property->navigation_url), '_blank');
            return;
        }

        navigator.geolocation.getCurrentPosition((position) => {
            const origin = `${position.coords.latitude},${position.coords.longitude}`;
            const destination = `${propertyPoint.lat},${propertyPoint.lng}`;
            const url = `https://www.google.com/maps/dir/?api=1&origin=${origin}&destination=${destination}`;
            window.open(url, '_blank');
        }, () => {
            window.open(@json($property->navigation_url), '_blank');
        });
    });
</script>
@endif
@endsection
