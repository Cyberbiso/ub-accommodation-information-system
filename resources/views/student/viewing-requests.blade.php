@extends('layouts.app')

@section('title', 'My Viewing Requests')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">My Viewing Requests</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('student.viewing-requests') }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status') ? 'bg-gray-100 text-gray-700' : 'bg-red-800 text-white' }}">All</a>
                    @foreach(['pending', 'approved', 'rejected', 'completed'] as $status)
                        <a href="{{ route('student.viewing-requests', ['status' => $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status') === $status ? 'bg-red-800 text-white' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($status) }}</a>
                    @endforeach
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($viewingRequests as $viewingRequest)
                    <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $viewingRequest->property->title ?? 'Property unavailable' }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $viewingRequest->status === 'approved' ? 'bg-green-100 text-green-800' : ($viewingRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : ($viewingRequest->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($viewingRequest->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Requested for {{ $viewingRequest->preferred_date?->format('d M Y') ?? 'Date not provided' }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $viewingRequest->property->city ?? 'Location unavailable' }} @if($viewingRequest->property) • P{{ number_format($viewingRequest->property->monthly_rent, 2) }}/month @endif</p>
                            @if($viewingRequest->scheduled_date)
                                <p class="text-sm text-green-700 mt-2">Scheduled: {{ $viewingRequest->scheduled_date->format('d M Y H:i') }}</p>
                            @endif
                            @if($viewingRequest->landlord_response)
                                <p class="text-sm text-gray-700 mt-2">{{ $viewingRequest->landlord_response }}</p>
                            @endif
                        </div>
                        @if($viewingRequest->property)
                            <a href="{{ route('student.properties.show', $viewingRequest->property) }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">Open property</a>
                        @else
                            <span class="text-sm text-gray-500">Property no longer available</span>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-calendar-alt text-5xl text-gray-300"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mt-4">No viewing requests yet</h3>
                        <p class="text-gray-600 mt-2">Browse verified listings and request a viewing when something fits.</p>
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $viewingRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
