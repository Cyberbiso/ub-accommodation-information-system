@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Admin Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900">Welcome, {{ Auth::user()->name }}!</h3>
                <p class="text-gray-600 mt-1">System overview and management dashboard.</p>
            </div>
        </div>

        <!-- Statistics Cards - Row 1 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <i class="fas fa-users text-red-800 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                            <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Students</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_students'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-home text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Landlords</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_landlords'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                            <i class="fas fa-building text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Properties</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_properties'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards - Row 2 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Properties</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['pending_properties'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
                            <i class="fas fa-check-circle text-indigo-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved Props</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['approved_properties'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-pink-100 rounded-md p-3">
                            <i class="fas fa-file-alt text-pink-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Applications</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $stats['total_applications'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd class="text-2xl font-semibold text-gray-900">P{{ number_format($stats['total_payments'], 2) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('admin.users') }}" class="app-card bg-white p-6 hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Manage Users</h3>
                    <p class="text-gray-600 text-sm">View and manage all users</p>
                </div>
                <i class="fas fa-users-cog text-red-800 text-2xl group-hover:scale-110 transition"></i>
            </a>
            
            <a href="{{ route('admin.properties.pending') }}" class="app-card bg-white p-6 hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Review Properties</h3>
                    <p class="text-gray-600 text-sm">{{ $stats['pending_properties'] }} pending approvals</p>
                </div>
                <i class="fas fa-clipboard-check text-red-800 text-2xl group-hover:scale-110 transition"></i>
            </a>
            
            <a href="{{ route('information.index') }}" class="app-card bg-white p-6 hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Manage Content</h3>
                    <p class="text-gray-600 text-sm">Update information pages</p>
                </div>
                <i class="fas fa-edit text-red-800 text-2xl group-hover:scale-110 transition"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
                    <a href="{{ route('admin.users') }}" class="text-sm app-link">View All</a>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($recent_users as $user)
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            </div>
                            <span class="app-badge 
                                @if($user->role == 'admin') app-badge-danger
                                @elseif($user->role == 'welfare') app-badge-warning
                                @elseif($user->role == 'landlord') app-badge-info
                                @else app-badge-success @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Pending Property Approvals -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    <a href="{{ route('admin.properties.pending') }}" class="text-sm app-link">View All</a>
                </div>
                <div class="p-6">
                    @if($pending_properties->count() > 0)
                        <div class="space-y-4">
                            @foreach($pending_properties as $property)
                            <div class="border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $property->title }}</p>
                                        <p class="text-sm text-gray-600">Landlord: {{ $property->landlord->name }}</p>
                                        <p class="text-xs text-gray-500">P{{ number_format($property->monthly_rent, 2) }}/month</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <form action="{{ route('admin.properties.approve', $property) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>
                                        <button onclick="openRejectModal({{ $property->id }})" class="text-xs bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700">
                                            Reject
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 text-center py-4">No pending property approvals.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">Reject Property</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection</label>
                <textarea name="reason" rows="3" class="w-full px-3 py-2 border rounded-lg" required></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Reject Property</button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(propertyId) {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
    document.getElementById('rejectForm').action = '/admin/properties/' + propertyId + '/reject';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
@endsection