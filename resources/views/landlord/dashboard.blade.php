@extends('layouts.app')

@section('title', 'Landlord Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Landlord Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-red-700 font-semibold">Landlord Portal</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ Auth::user()->company_name ?? Auth::user()->name }}</h1>
                    <p class="text-gray-600 mt-2">Advertise verified accommodation, manage viewing requests, and track confirmed student bookings.</p>
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    @php
                        $vstatus = Auth::user()->landlord_verification_status;
                        $statusBadge = match(true) {
                            Auth::user()->isVerifiedLandlord()   => ['bg-green-100 text-green-800', 'Verified'],
                            $vstatus === 'rejected'              => ['bg-red-100 text-red-800', 'Rejected'],
                            $vstatus === 'needs_more_info'       => ['bg-blue-100 text-blue-800', 'More info required'],
                            $vstatus === 'pending'               => ['bg-amber-100 text-amber-800', 'Under review'],
                            default                              => ['bg-gray-100 text-gray-700', 'Not started'],
                        };
                    @endphp
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $statusBadge[0] }}">
                        {{ $statusBadge[1] }}
                    </span>
                    <a href="{{ route('landlord.verification') }}" class="px-4 py-2 rounded-lg text-sm font-semibold bg-red-800 text-white hover:bg-red-900 transition">
                        Open verification
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-4">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Properties</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_properties'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600 mt-2">{{ $stats['active_properties'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Units Available</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ $stats['available_units'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Pending Reviews</p>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_reviews'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Viewings</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_viewing_requests'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Pending Viewings</p>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_viewings'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Enquiries</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $stats['pending_enquiries'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Confirmed Bookings</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['confirmed_bookings'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('landlord.properties.create') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Listings</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Advertise a property</h3>
                <p class="text-gray-600 mt-2 text-sm">Add location, amenities, transport routes, and GPS coordinates.</p>
            </a>
            <a href="{{ route('landlord.viewing-requests') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Students</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Manage viewings</h3>
                <p class="text-gray-600 mt-2 text-sm">Approve or decline pending requests.</p>
            </a>
            <a href="{{ route('landlord.bookings') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Bookings</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Track confirmed stays</h3>
                <p class="text-gray-600 mt-2 text-sm">Review selected accommodation and payment outcomes.</p>
            </a>
            <a href="{{ route('landlord.enquiries') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Messages</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Respond to enquiries</h3>
                <p class="text-gray-600 mt-2 text-sm">Reply to student questions about your listings.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Latest Properties</h3>
                    <a href="{{ route('landlord.properties') }}" class="text-sm text-red-800 hover:underline">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_properties as $property)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <p class="font-semibold text-gray-900">{{ $property->title }}</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->review_status === 'approved' ? 'bg-green-100 text-green-800' : ($property->review_status === 'changes_requested' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst(str_replace('_', ' ', $property->review_status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $property->city }} • P{{ number_format($property->monthly_rent, 2) }}/month</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->is_available ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $property->available_units }} unit{{ $property->available_units > 1 ? 's' : '' }}
                                </span>
                                <a href="{{ route('landlord.properties.edit', $property) }}" class="text-sm text-red-800 hover:underline">Edit</a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No properties listed yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Recent Bookings</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recent_bookings as $booking)
                        <div class="p-5">
                            <p class="font-semibold text-gray-900">{{ $booking->property->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $booking->student->name }} • {{ $booking->booking_reference }}</p>
                            <span class="inline-flex mt-3 px-3 py-1 rounded-full text-xs font-semibold {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No bookings yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Enquiries</h3>
                <a href="{{ route('landlord.enquiries') }}" class="text-sm text-red-800 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recent_enquiries as $enquiry)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <p class="font-semibold text-gray-900">{{ $enquiry->subject }}</p>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $enquiry->status === 'responded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $enquiry->student->name }} • {{ $enquiry->property->title }}</p>
                        </div>
                        <a href="{{ route('landlord.enquiries') }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Respond</a>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No enquiries yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
