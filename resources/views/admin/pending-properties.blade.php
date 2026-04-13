@extends('layouts.app')

@section('title', 'Property Reviews')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Property Reviews</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Review and verify property listings</h1>
                    <p class="text-gray-600 mt-2">Approve, request changes, reject, or remove property listings before or after they go live.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'changes_requested' => 'Changes Requested', 'approved' => 'Approved', 'rejected' => 'Rejected', 'removed' => 'Removed'] as $status => $label)
                        <a href="{{ route('admin.properties.pending', ['status' => $status === 'all' ? null : $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status', 'all') === $status || ($status === 'all' && !request('status')) ? 'bg-red-800 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($properties as $property)
                <div class="bg-white rounded-2xl shadow overflow-hidden">
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2 space-y-5">
                            <div class="flex items-start justify-between gap-4 flex-wrap">
                                <div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <h3 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->review_status === 'approved' ? 'bg-green-100 text-green-800' : ($property->review_status === 'rejected' || $property->review_status === 'removed' ? 'bg-red-100 text-red-800' : ($property->review_status === 'changes_requested' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $property->review_status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ $property->full_address }}</p>
                                    <p class="text-sm text-gray-600 mt-1">Landlord: {{ $property->landlord->company_name ?? $property->landlord->name }} • P{{ number_format($property->monthly_rent, 2) }}/month</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $property->campus_distance_label }} • {{ ucfirst($property->type) }} • {{ $property->bedrooms }} bed / {{ $property->bathrooms }} bath</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $property->available_from_label }}</p>
                                    @if($property->hasLeaseAgreement())
                                        <a href="{{ route('documents.property-lease.show', $property) }}" target="_blank" class="inline-flex mt-2 text-sm text-red-800 hover:underline">Open lease agreement</a>
                                    @endif
                                </div>
                            </div>

                            @if($property->photo_urls)
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @foreach($property->photo_urls as $photo)
                                        <img src="{{ $photo }}" alt="{{ $property->title }}" class="w-full h-32 object-cover rounded-xl border border-gray-200">
                                    @endforeach
                                </div>
                            @endif

                            <div class="bg-gray-50 rounded-xl p-5">
                                <h4 class="font-semibold text-gray-900">Description</h4>
                                <p class="text-gray-700 mt-3">{{ $property->description }}</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-semibold text-gray-900">Amenities</p>
                                    <p class="text-sm text-gray-600 mt-2">{{ $property->amenities ? implode(', ', $property->amenities) : 'None added' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-semibold text-gray-900">Transport</p>
                                    <p class="text-sm text-gray-600 mt-2">{{ $property->transport_routes ? implode(', ', $property->transport_routes) : 'None added' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-4">
                                    <p class="font-semibold text-gray-900">Nearby amenities</p>
                                    <p class="text-sm text-gray-600 mt-2">{{ $property->nearby_amenities ? implode(', ', $property->nearby_amenities) : 'None added' }}</p>
                                </div>
                            </div>

                            @if($property->review_notes)
                                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Latest review notes:</span>
                                    {{ $property->review_notes }}
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">
                            <form method="POST" action="{{ route('admin.properties.review', $property) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Review notes</label>
                                    <textarea name="notes" rows="8" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Approval notes, change requests, or removal reason">{{ $property->review_notes }}</textarea>
                                </div>
                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                    <input type="checkbox" name="suspend_landlord" value="1" class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                                    Suspend landlord account when removing listing
                                </label>
                                <button type="submit" name="action" value="approve" class="w-full bg-green-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-800 transition">Approve listing</button>
                                <button type="submit" name="action" value="request_changes" class="w-full bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-800 transition">Request changes</button>
                                <button type="submit" name="action" value="reject" class="w-full bg-amber-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-amber-800 transition">Reject listing</button>
                                <button type="submit" name="action" value="remove" class="w-full bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition">Remove listing</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow p-12 text-center text-gray-500">No property listings match the current filter.</div>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow px-6 py-4">
            {{ $properties->links() }}
        </div>
    </div>
</div>
@endsection
