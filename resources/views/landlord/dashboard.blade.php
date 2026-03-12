@extends('layouts.app')

@section('title', 'Landlord Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Landlord Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900">Welcome back, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mt-1">Manage your properties, viewing requests, and listings from your dashboard.</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <i class="fas fa-building text-red-800 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Properties</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_properties'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Listings</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['active_properties'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Approvals</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_approvals'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Viewing Requests</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_viewing_requests'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <a href="{{ route('landlord.properties.create') }}" class="app-card bg-white p-6 hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Add New Property</h3>
                    <p class="text-gray-600 text-sm">List a new property for students</p>
                </div>
                <i class="fas fa-plus-circle text-red-800 text-2xl group-hover:scale-110 transition"></i>
            </a>
            
            <a href="{{ route('landlord.viewing-requests') }}" class="app-card bg-white p-6 hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Manage Viewings</h3>
                    <p class="text-gray-600 text-sm">{{ $stats['pending_viewings'] }} pending requests</p>
                </div>
                <i class="fas fa-calendar-alt text-red-800 text-2xl group-hover:scale-110 transition"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Properties -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Your Properties</h3>
                    <a href="{{ route('landlord.properties') }}" class="text-sm app-link">View All</a>
                </div>
                <div class="p-6">
                    @if($recent_properties->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_properties as $property)
                            <div class="flex justify-between items-center border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                    <p class="text-sm text-gray-600">{{ $property->city }} • P{{ number_format($property->monthly_rent, 2) }}/month</p>
                                </div>
                                <span class="app-badge 
                                    @if($property->is_approved) app-badge-success
                                    @else app-badge-warning @endif">
                                    {{ $property->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-4">No properties listed yet.</p>
                        <div class="text-center">
                            <a href="{{ route('landlord.properties.create') }}" class="app-button inline-block">
                                Add Your First Property
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Viewing Requests -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Viewing Requests</h3>
                    <a href="{{ route('landlord.viewing-requests') }}" class="text-sm app-link">View All</a>
                </div>
                <div class="p-6">
                    @if($recent_requests->count() > 0)
                        <div class="space-y-4">
                            @foreach($recent_requests as $request)
                            <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $request->property->title }}</p>
                                        <p class="text-sm text-gray-600">Student: {{ $request->student->name }}</p>
                                        <p class="text-xs text-gray-500">Preferred: {{ $request->preferred_date->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <span class="app-badge 
                                        @if($request->status == 'pending') app-badge-warning
                                        @elseif($request->status == 'approved') app-badge-success
                                        @elseif($request->status == 'rejected') app-badge-danger
                                        @else app-badge-info @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </div>
                                @if($request->status == 'pending')
                                <div class="mt-2 flex space-x-2">
                                    <button onclick="openScheduleModal({{ $request->id }})" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                        Approve
                                    </button>
                                    <button onclick="openRejectModal({{ $request->id }})" class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                        Reject
                                    </button>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-4">No viewing requests yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal (hidden by default) -->
<div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Schedule Viewing</h3>
        <form id="scheduleForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Date & Time</label>
                <input type="datetime-local" name="scheduled_date" class="w-full px-3 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Message to Student (Optional)</label>
                <textarea name="message" rows="3" class="w-full px-3 py-2 border rounded-lg"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeScheduleModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="app-button">Confirm</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Reject Viewing Request</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2 border rounded-lg" required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Reject Request</button>
            </div>
        </form>
    </div>
</div>

<script>
function openScheduleModal(requestId) {
    document.getElementById('scheduleModal').classList.remove('hidden');
    document.getElementById('scheduleModal').classList.add('flex');
    document.getElementById('scheduleForm').action = '/landlord/viewing-requests/' + requestId + '/approve';
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleModal').classList.remove('flex');
}

function openRejectModal(requestId) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.getElementById('rejectForm').action = '/landlord/viewing-requests/' + requestId + '/reject';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
@endsection