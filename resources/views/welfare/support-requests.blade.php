@extends('layouts.app')

@section('title', 'Support Requests')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Support Requests</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h1 class="text-2xl font-bold text-gray-900">Virtual help desk queue</h1>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($supportRequests as $ticket)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ in_array($ticket->status, ['resolved', 'closed']) ? 'bg-green-100 text-green-800' : ($ticket->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $ticket->student->name }} • {{ ucfirst($ticket->category) }} • {{ ucfirst($ticket->priority) }} priority • {{ $ticket->reference }}</p>
                            <p class="text-gray-700 mt-4">{{ $ticket->description }}</p>
                            @if($ticket->resolution_notes)
                                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Latest response:</span>
                                    {{ $ticket->resolution_notes }}
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('welfare.support.update', $ticket) }}" class="space-y-4 bg-gray-50 rounded-xl p-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                    @foreach(['open', 'in_progress', 'resolved', 'closed'] as $status)
                                        <option value="{{ $status }}" {{ $ticket->status === $status ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Response / Notes</label>
                                <textarea name="resolution_notes" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Update ticket</button>
                        </form>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No support requests found.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $supportRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
