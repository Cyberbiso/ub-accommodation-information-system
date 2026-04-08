@extends('layouts.app')

@section('title', 'Welfare Officer Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Welfare Officer Dashboard</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-8 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-red-700 font-semibold">Student Welfare</p>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">{{ Auth::user()->name }}</h1>
                    <p class="text-gray-600 mt-2">Allocate rooms, verify student and landlord documents, and respond to onboarding support requests.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('welfare.applications') }}" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">Applications</a>
                    <a href="{{ route('welfare.landlords.verifications') }}" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">Landlords</a>
                    <a href="{{ route('welfare.support') }}" class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50">Help Desk</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-8 gap-4">
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
                <p class="text-xs uppercase text-gray-500">Rejected</p>
                <p class="text-2xl font-bold text-red-600 mt-2">{{ $stats['rejected_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Available Rooms</p>
                <p class="text-2xl font-bold text-blue-600 mt-2">{{ $stats['available_rooms'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Occupancy</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['occupancy_rate'] }}%</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Landlord Checks</p>
                <p class="text-2xl font-bold text-purple-600 mt-2">{{ $stats['pending_landlord_verifications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-4">
                <p class="text-xs uppercase text-gray-500">Help Desk</p>
                <p class="text-2xl font-bold text-indigo-600 mt-2">{{ $stats['open_support_requests'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('welfare.applications', ['status' => 'pending']) }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Allocations</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Review room requests</h3>
                <p class="text-gray-600 mt-2 text-sm">{{ $stats['pending_applications'] }} applications waiting for a decision.</p>
            </a>
            <a href="{{ route('welfare.landlords.verifications') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Verification</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Process landlord stages</h3>
                <p class="text-gray-600 mt-2 text-sm">Approve company registration, tax, and ownership checks in sequence.</p>
            </a>
            <a href="{{ route('welfare.support') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <p class="text-sm font-semibold text-red-700">Support</p>
                <h3 class="text-xl font-bold text-gray-900 mt-2">Answer help-desk tickets</h3>
                <p class="text-gray-600 mt-2 text-sm">Students can track immigration, registration, and accommodation support.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-900">Recent Applications</h3>
                    <a href="{{ route('welfare.applications') }}" class="text-sm text-red-800 hover:underline">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentApplications as $application)
                        <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $application->student->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $application->student->isInternational() ? 'International student' : 'Local student' }} • {{ $application->created_at->format('d M Y') }}</p>
                            </div>
                            <a href="{{ route('welfare.applications.show', $application) }}" class="text-sm text-red-800 hover:underline">Review</a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No applications yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Landlord Verification Queue</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($pendingLandlords as $landlord)
                        <div class="p-5">
                            <p class="font-semibold text-gray-900">{{ $landlord->company_name ?? $landlord->name }}</p>
                            <p class="text-sm text-gray-600 mt-1">Current stage: {{ ucfirst(str_replace('_', ' ', $landlord->landlord_verification_stage)) }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">No landlords pending.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Open Help Desk Tickets</h3>
                <a href="{{ route('welfare.support') }}" class="text-sm text-red-800 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($supportQueue as $ticket)
                    <div class="p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $ticket->subject }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $ticket->student->name }} • {{ ucfirst($ticket->category) }} • {{ $ticket->reference }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                        </span>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">No open tickets right now.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
