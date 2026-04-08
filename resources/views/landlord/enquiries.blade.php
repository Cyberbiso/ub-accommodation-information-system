@extends('layouts.app')

@section('title', 'Property Enquiries')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Property Enquiries</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Student messages about your properties</h1>
                    <p class="text-gray-600 mt-2">Reply to questions from students and keep a clear communication trail for each listing.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'responded' => 'Responded'] as $status => $label)
                        <a href="{{ route('landlord.enquiries', ['status' => $status === 'all' ? null : $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status', 'all') === $status || ($status === 'all' && !request('status')) ? 'bg-red-800 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="divide-y divide-gray-100">
                @forelse($enquiries as $enquiry)
                    <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                        <div class="xl:col-span-2">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $enquiry->subject }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $enquiry->status === 'responded' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $enquiry->student->name }} • {{ $enquiry->property->title }} • {{ $enquiry->reference }}</p>
                            <p class="text-gray-700 mt-4">{{ $enquiry->message }}</p>
                            @if($enquiry->response)
                                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Your latest response:</span>
                                    {{ $enquiry->response }}
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-6">
                            <form method="POST" action="{{ route('landlord.enquiries.respond', $enquiry) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Respond to enquiry</label>
                                    <textarea name="response" rows="7" class="w-full border border-gray-300 rounded-lg px-4 py-3" placeholder="Type your response to the student">{{ $enquiry->response }}</textarea>
                                </div>
                                <button type="submit" class="w-full bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Send response</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No student enquiries yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $enquiries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
