@extends('layouts.app')

@section('title', 'Virtual Help Desk')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Virtual Help Desk</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 xl:grid-cols-3 gap-6">
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl shadow p-6">
                <h1 class="text-2xl font-bold text-gray-900">Submit a request</h1>
                <p class="text-gray-600 mt-2">Ask for help with immigration, registration, accommodation, or onboarding.</p>

                <form method="POST" action="{{ route('student.support.store') }}" class="space-y-4 mt-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            @foreach($categories as $category)
                                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <input type="text" name="subject" class="w-full border border-gray-300 rounded-lg px-4 py-3" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Describe the issue</label>
                        <textarea name="description" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3" required></textarea>
                    </div>
                    <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Submit request</button>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h2 class="text-2xl font-bold text-gray-900">My support requests</h2>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($supportRequests as $ticket)
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <h3 class="text-xl font-bold text-gray-900">{{ $ticket->subject }}</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ in_array($ticket->status, ['resolved', 'closed']) ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-2">{{ ucfirst($ticket->category) }} • {{ ucfirst($ticket->priority) }} priority • {{ $ticket->reference }}</p>
                                    <p class="text-gray-700 mt-4">{{ $ticket->description }}</p>
                                    @if($ticket->resolution_notes)
                                        <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                            <span class="font-semibold text-gray-900">Response:</span>
                                            {{ $ticket->resolution_notes }}
                                        </div>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $ticket->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">No support requests submitted yet.</div>
                    @endforelse
                </div>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $supportRequests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
