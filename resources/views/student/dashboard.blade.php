@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Student Dashboard</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-red-700 font-semibold">UniStay</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-600 mt-2 max-w-2xl">
                        {{ Auth::user()->isInternational() ? 'International student support is active on your account. Use the help desk for immigration and onboarding questions.' : 'Track accommodation applications, payments, and support requests from one place.' }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ Auth::user()->document_status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        Documents: {{ ucfirst(Auth::user()->document_status) }}
                    </span>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ Auth::user()->isInternational() ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700' }}">
                        {{ Auth::user()->isInternational() ? 'International Student' : 'Local Student' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-9 gap-4">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Applications</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Approved</p>
                <p class="text-2xl font-bold text-green-600 mt-2">{{ $stats['approved_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Bookings</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ $stats['off_campus_bookings'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Viewings</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_viewings'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Upcoming</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $stats['upcoming_viewings'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Pending Payments</p>
                <p class="text-2xl font-bold text-red-700 mt-2">{{ $stats['pending_payments'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Help Desk</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['open_support_requests'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Enquiries</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $stats['property_enquiries'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <a href="{{ route('student.apply.form') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">On-Campus</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Apply for Housing</h3>
                <p class="text-gray-600 mt-2 text-sm">Submit your residence application and room preferences.</p>
            </a>
            <a href="{{ route('student.properties') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Off-Campus</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Browse Verified Properties</h3>
                <p class="text-gray-600 mt-2 text-sm">Explore map-ready listings with transport routes and amenities.</p>
            </a>
            <a href="{{ route('student.bookings') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Payments</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Manage Bookings</h3>
                <p class="text-gray-600 mt-2 text-sm">Pay for selected off-campus accommodation and track status.</p>
            </a>
            <a href="{{ route('student.support') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Support</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Virtual Help Desk</h3>
                <p class="text-gray-600 mt-2 text-sm">Get help with immigration, registration, accommodation, and onboarding.</p>
            </a>
            <a href="{{ route('student.enquiries') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Messages</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Track Enquiries</h3>
                <p class="text-gray-600 mt-2 text-sm">Follow up on questions sent to landlords about properties.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Applications</h3>
                    <a href="{{ route('student.applications') }}" class="text-sm text-red-800 hover:underline">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentApplications as $application)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $application->accommodation->name ?? 'General room allocation request' }}</p>
                                <p class="text-sm text-gray-600">Submitted {{ $application->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No applications yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Help Desk Snapshot</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($supportRequests as $ticket)
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-gray-900">{{ $ticket->subject }}</p>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ in_array($ticket->status, ['resolved', 'closed']) ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ ucfirst($ticket->category) }} • {{ $ticket->reference }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No support requests submitted yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Off-Campus Bookings</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentBookings as $booking)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $booking->property->title }}</p>
                                <p class="text-sm text-gray-600">{{ $booking->booking_reference }} • Move in {{ $booking->move_in_date?->format('d M Y') ?? 'Not set' }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No off-campus bookings yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Recent Payments</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentPayments as $payment)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $payment->type_label }}</p>
                                <p class="text-sm text-gray-600">{{ $payment->formatted_amount }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No payments recorded yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Recent Property Enquiries</h3>
                <a href="{{ route('student.enquiries') }}" class="text-sm text-red-800 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentEnquiries as $enquiry)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <p class="font-semibold text-gray-900">{{ $enquiry->subject }}</p>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $enquiry->status === 'responded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $enquiry->property->title }} • {{ $enquiry->landlord->company_name ?? $enquiry->landlord->name }}</p>
                        </div>
                        <a href="{{ route('student.properties.show', $enquiry->property) }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Open property</a>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No property enquiries sent yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
