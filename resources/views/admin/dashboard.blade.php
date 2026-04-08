@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Admin Dashboard</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-red-700 font-semibold">Administration</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">Govern the accommodation portal</h1>
                    <p class="text-gray-600 mt-2 max-w-3xl">Review landlord verification packages, moderate property listings, manage users, and publish announcements for students, landlords, welfare, and admins.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.landlords.verifications') }}" class="px-4 py-3 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition">Review landlords</a>
                    <a href="{{ route('admin.properties.pending') }}" class="px-4 py-3 rounded-lg bg-red-800 text-white font-semibold hover:bg-red-900 transition">Review properties</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-4">
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Users</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Active</p>
                <p class="text-2xl font-bold text-green-600 mt-2">{{ $stats['active_users'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Suspended</p>
                <p class="text-2xl font-bold text-red-700 mt-2">{{ $stats['inactive_users'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Landlord Reviews</p>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_landlord_verifications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Verified Landlords</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ $stats['verified_landlords'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Property Queue</p>
                <p class="text-2xl font-bold text-amber-600 mt-2">{{ $stats['pending_properties'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Announcements</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $stats['total_announcements'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">P{{ number_format($stats['total_payments'], 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.users') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Users</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Manage accounts</h3>
                <p class="text-gray-600 mt-2 text-sm">Add, edit, deactivate, or delete portal users.</p>
            </a>
            <a href="{{ route('admin.landlords.verifications') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Verification</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Verify landlords</h3>
                <p class="text-gray-600 mt-2 text-sm">Approve, reject, or request more information.</p>
            </a>
            <a href="{{ route('admin.properties.pending') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Listings</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Moderate properties</h3>
                <p class="text-gray-600 mt-2 text-sm">Review new listings before they go live.</p>
            </a>
            <a href="{{ route('admin.announcements') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Comms</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Publish announcements</h3>
                <p class="text-gray-600 mt-2 text-sm">Target students, landlords, welfare, or everyone.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Pending Landlord Verifications</h3>
                    <a href="{{ route('admin.landlords.verifications') }}" class="text-sm text-red-800 hover:underline">Open queue</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingLandlords as $landlord)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <p class="font-semibold text-gray-900">{{ $landlord->company_name ?? $landlord->name }}</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $landlord->landlord_verification_status === 'needs_more_info' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $landlord->email }} • Stage: {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_stage)) }}</p>
                            </div>
                            <a href="{{ route('admin.landlords.verifications') }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Review</a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No landlord verifications waiting right now.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Recent Announcements</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentAnnouncements as $announcement)
                        <div class="p-5">
                            <div class="flex items-center gap-3 flex-wrap">
                                <p class="font-semibold text-gray-900">{{ $announcement->title }}</p>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $announcement->priority === 'important' ? 'bg-red-100 text-red-800' : ($announcement->priority === 'warning' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $announcement->target_role ? ucfirst($announcement->target_role) : 'All users' }}</p>
                            <p class="text-sm text-gray-700 mt-3">{{ \Illuminate\Support\Str::limit($announcement->content, 120) }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No announcements published yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Pending Property Reviews</h3>
                    <a href="{{ route('admin.properties.pending') }}" class="text-sm text-red-800 hover:underline">Open queue</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingProperties as $property)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-3 flex-wrap">
                                    <p class="font-semibold text-gray-900">{{ $property->title }}</p>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $property->review_status === 'changes_requested' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst(str_replace('_', ' ', $property->review_status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $property->landlord->company_name ?? $property->landlord->name }} • P{{ number_format($property->monthly_rent, 2) }}/month</p>
                            </div>
                            <a href="{{ route('admin.properties.pending') }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Review</a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No property submissions pending review.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Recent Users</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentUsers as $user)
                        <div class="p-5 flex items-center justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $user->email }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                <p class="text-xs text-gray-500 mt-2">{{ ucfirst($user->role) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No users found.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
