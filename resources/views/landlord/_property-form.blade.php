@php
    $amenitiesInput = old('amenities_input', isset($property) ? implode(', ', $property->amenities ?? []) : '');
    $transportInput = old('transport_routes_input', isset($property) ? implode(', ', $property->transport_routes ?? []) : '');
    $nearbyInput = old('nearby_amenities_input', isset($property) ? implode(', ', $property->nearby_amenities ?? []) : '');
    $googleMapsLocation = old(
        'google_maps_location',
        isset($property) && $property->hasCoordinates()
            ? 'https://www.google.com/maps?q=' . $property->latitude . ',' . $property->longitude
            : ''
    );
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Listing Title</label>
        <input type="text" name="title" value="{{ old('title', $property->title ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>{{ old('description', $property->description ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
        <input type="text" name="address" value="{{ old('address', $property->address ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
        <input type="text" name="city" value="{{ old('city', $property->city ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
        <input type="text" name="postal_code" value="{{ old('postal_code', $property->postal_code ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
        <select name="type" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
            @foreach(['apartment', 'house', 'shared', 'studio'] as $type)
                <option value="{{ $type }}" {{ old('type', $property->type ?? '') === $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Monthly Rent (P)</label>
        <input type="number" step="0.01" min="0" name="monthly_rent" value="{{ old('monthly_rent', $property->monthly_rent ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Deposit Amount (P)</label>
        <input type="number" step="0.01" min="0" name="deposit_amount" value="{{ old('deposit_amount', $property->deposit_amount ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
        <input type="number" min="0" name="bedrooms" value="{{ old('bedrooms', $property->bedrooms ?? 1) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
        <input type="number" min="0" name="bathrooms" value="{{ old('bathrooms', $property->bathrooms ?? 1) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Available Units</label>
        <input type="number" min="1" name="available_units" value="{{ old('available_units', $property->available_units ?? 1) }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Availability Date</label>
        <input type="date" name="available_from" value="{{ old('available_from', isset($property) && $property->available_from ? $property->available_from->toDateString() : '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
        <p class="text-xs text-gray-500 mt-2">Students will see this date before booking and cannot choose an earlier move-in date.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Distance to Campus (km)</label>
        <input type="number" step="0.1" min="0" name="distance_to_campus_km" value="{{ old('distance_to_campus_km', $property->distance_to_campus_km ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
        <p class="text-xs text-gray-500 mt-2">Leave this blank if you paste a Google Maps pin below. We will calculate the distance automatically.</p>
    </div>
    <div class="md:col-span-2 rounded-2xl border border-gray-200 bg-gray-50 p-5">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
            <div>
                <label for="googleMapsLocationInput" class="block text-sm font-medium text-gray-700 mb-2">Google Maps Location</label>
                <p class="text-sm text-gray-600">Open Google Maps, drop a pin on the property, then paste the full browser link here. You can also paste a coordinate pair like <span class="font-medium">-24.6282, 25.9231</span>.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="https://www.google.com/maps" target="_blank" rel="noreferrer" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                    Open Google Maps
                </a>
                <a id="googleMapsPreviewLink"
                   href="{{ $googleMapsLocation ?: 'https://www.google.com/maps' }}"
                   target="_blank"
                   rel="noreferrer"
                   class="inline-flex items-center rounded-lg border border-red-200 bg-white px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-50 transition {{ $googleMapsLocation ? '' : 'pointer-events-none opacity-50' }}">
                    Preview Pin
                </a>
            </div>
        </div>

        <div class="mt-4">
            <input type="text"
                   id="googleMapsLocationInput"
                   name="google_maps_location"
                   value="{{ $googleMapsLocation }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-3"
                   placeholder="https://www.google.com/maps/place/... or -24.6282, 25.9231">
            <p class="text-xs text-gray-500 mt-2">Tip: avoid short <span class="font-medium">maps.app.goo.gl</span> links because they do not include coordinates the system can read automatically.</p>
            @error('google_maps_location')
                <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
            @enderror
            @if(isset($property) && $property->hasCoordinates())
                <p class="text-xs text-gray-500 mt-2">
                    Current saved pin:
                    <a href="https://www.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}" target="_blank" rel="noreferrer" class="text-red-700 hover:underline">Open current location</a>
                </p>
            @endif
        </div>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
        <input type="text" name="amenities_input" value="{{ $amenitiesInput }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="WiFi, Security, Furnished">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Transport Routes</label>
        <input type="text" name="transport_routes_input" value="{{ $transportInput }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="UB shuttle, Combi route 4, Main road taxi rank">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Nearby Amenities</label>
        <input type="text" name="nearby_amenities_input" value="{{ $nearbyInput }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Clinic, Supermarket, ATM, Bus stop">
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Navigation Notes</label>
        <textarea name="navigation_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Landmark or gate instructions">{{ old('navigation_notes', $property->navigation_notes ?? '') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Lease Agreement</label>
        <input type="file" name="lease_agreement" class="block w-full text-sm text-gray-700">
        <p class="text-xs text-gray-500 mt-2">Upload the lease agreement students should download, sign, and submit back with their booking.</p>
        @if(!empty($property?->lease_agreement_path))
            <p class="text-xs text-gray-500 mt-2">
                Current lease:
                <a href="{{ route('documents.property-lease.show', $property) }}" target="_blank" class="text-red-700 hover:underline">{{ $property->lease_agreement_original_name }}</a>
            </p>
        @endif
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-2">Property Photos</label>
        <input type="file" name="photos[]" multiple class="block w-full text-sm text-gray-700">
        <p class="text-xs text-gray-500 mt-2">Upload multiple photos to help students review the property before booking.</p>
        @if(!empty($property?->photo_urls))
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-4">
                @foreach($property->photo_urls as $photo)
                    <img src="{{ $photo }}" alt="{{ $property->title }}" class="w-full h-28 object-cover rounded-xl border border-gray-200">
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('googleMapsLocationInput');
    const previewLink = document.getElementById('googleMapsPreviewLink');

    if (!input || !previewLink) {
        return;
    }

    const defaultHref = 'https://www.google.com/maps';
    const coordinatePattern = /(-?\d{1,3}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)/;

    const updatePreview = () => {
        const value = input.value.trim();

        if (!value) {
            previewLink.href = defaultHref;
            previewLink.classList.add('pointer-events-none', 'opacity-50');
            return;
        }

        const coordinateMatch = value.match(coordinatePattern);
        if (coordinateMatch) {
            previewLink.href = `https://www.google.com/maps?q=${coordinateMatch[1]},${coordinateMatch[2]}`;
            previewLink.classList.remove('pointer-events-none', 'opacity-50');
            return;
        }

        if (/^https?:\/\//i.test(value)) {
            previewLink.href = value;
            previewLink.classList.remove('pointer-events-none', 'opacity-50');
            return;
        }

        previewLink.href = defaultHref;
        previewLink.classList.add('pointer-events-none', 'opacity-50');
    };

    input.addEventListener('input', updatePreview);
    updatePreview();
});
</script>
