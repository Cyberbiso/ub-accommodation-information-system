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
                <h1 class="text-2xl font-bold text-gray-900">Student viewing requests</h1>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($requests as $request)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $request->property->title }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : ($request->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $request->student->name }} • Preferred {{ $request->preferred_date->format('d M Y') }}</p>
                            @if($request->landlord_response)
                                <p class="text-sm text-gray-700 mt-4">{{ $request->landlord_response }}</p>
                            @endif
                        </div>

                        @if($request->status === 'pending')
                            <div class="space-y-4">
                                <form method="POST" action="{{ route('landlord.viewing-requests.approve', $request) }}" class="space-y-3 bg-gray-50 rounded-xl p-4">
                                    @csrf
                                    <input type="datetime-local" name="scheduled_date" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                                    <textarea name="message" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Optional response"></textarea>
                                    <button type="submit" class="w-full bg-green-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-green-800 transition">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('landlord.viewing-requests.reject', $request) }}" class="space-y-3 bg-red-50 rounded-xl p-4">
                                    @csrf
                                    <textarea name="reason" rows="2" class="w-full border border-red-200 rounded-lg px-4 py-3" placeholder="Reason for rejection" required></textarea>
                                    <button type="submit" class="w-full bg-red-700 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-800 transition">Reject</button>
                                </form>
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-600">
                                @if($request->scheduled_date)
                                    Scheduled: {{ $request->scheduled_date->format('d M Y H:i') }}
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
