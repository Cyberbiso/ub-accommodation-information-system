@php
    $amenitiesInput = old('amenities_input', isset($property) ? implode(', ', $property->amenities ?? []) : '');
    $transportInput = old('transport_routes_input', isset($property) ? implode(', ', $property->transport_routes ?? []) : '');
    $nearbyInput = old('nearby_amenities_input', isset($property) ? implode(', ', $property->nearby_amenities ?? []) : '');
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
        <label class="block text-sm font-medium text-gray-700 mb-2">Distance to Campus (km)</label>
        <input type="number" step="0.1" min="0" name="distance_to_campus_km" value="{{ old('distance_to_campus_km', $property->distance_to_campus_km ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
        <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $property->latitude ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
        <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $property->longitude ?? '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3">
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
