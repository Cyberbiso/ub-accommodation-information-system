@extends('layouts.app')

@section('title', 'Viewing Requests')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Viewing Requests</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <h1 class="text-2xl font-bold text-gray-900">Student viewing requests</h1>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('landlord.viewing-requests') }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status') ? 'bg-gray-100 text-gray-700' : 'bg-red-800 text-white' }}">All</a>
                        @foreach(['pending', 'approved', 'rejected', 'completed'] as $status)
                            <a href="{{ route('landlord.viewing-requests', ['status' => $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status') === $status ? 'bg-red-800 text-white' : 'bg-gray-100 text-gray-700' }}">{{ ucfirst($status) }}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($requests as $viewingRequest)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $viewingRequest->property->title ?? 'Property unavailable' }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $viewingRequest->status === 'approved' ? 'bg-green-100 text-green-800' : ($viewingRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : ($viewingRequest->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                    {{ ucfirst($viewingRequest->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $viewingRequest->student->name ?? 'Student unavailable' }} • Preferred {{ $viewingRequest->preferred_date?->format('d M Y') ?? 'Date not provided' }}</p>
                            @if($viewingRequest->message)
                                <p class="text-sm text-gray-700 mt-4"><span class="font-semibold text-gray-900">Student note:</span> {{ $viewingRequest->message }}</p>
                            @endif
                            @if($viewingRequest->landlord_response)
                                <p class="text-sm text-gray-700 mt-4"><span class="font-semibold text-gray-900">Your response:</span> {{ $viewingRequest->landlord_response }}</p>
                            @endif
                        </div>

                        @if($viewingRequest->status === 'pending')
                            <div class="space-y-4">
                                <form method="POST" action="{{ route('landlord.viewing-requests.approve', $viewingRequest) }}" class="space-y-3 bg-gray-50 rounded-xl p-4">
                                    @csrf
                                    <input type="datetime-local" name="scheduled_date" min="{{ now()->addHour()->format('Y-m-d\\TH:i') }}" value="{{ old('scheduled_date') }}" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                                    <textarea name="message" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Optional response">{{ old('message') }}</textarea>
                                    <button type="submit" class="w-full bg-green-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-800 transition">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('landlord.viewing-requests.reject', $viewingRequest) }}" class="space-y-3 bg-red-50 rounded-xl p-4">
                                    @csrf
                                    <textarea name="reason" rows="2" class="w-full border border-red-200 rounded-lg px-4 py-3" placeholder="Reason for rejection" required>{{ old('reason') }}</textarea>
                                    <button type="submit" class="w-full bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition">Reject</button>
                                </form>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-600">
                                @if($viewingRequest->scheduled_date)
                                    Scheduled: {{ $viewingRequest->scheduled_date->format('d M Y H:i') }}
                                @else
                                    Request processed.
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No viewing requests yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
