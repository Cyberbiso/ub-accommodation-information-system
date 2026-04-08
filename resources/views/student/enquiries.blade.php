@extends('layouts.app')

@section('title', 'My Property Enquiries')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">My Property Enquiries</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Student-landlord enquiries</h1>
                    <p class="text-gray-600 mt-2">Track questions you have sent to landlords and review their responses.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @foreach(['all' => 'All', 'pending' => 'Pending', 'responded' => 'Responded'] as $status => $label)
                        <a href="{{ route('student.enquiries', ['status' => $status === 'all' ? null : $status]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold {{ request('status', 'all') === $status || ($status === 'all' && !request('status')) ? 'bg-red-800 text-white' : 'bg-white border border-gray-300 text-gray-700' }}">
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
                            <p class="text-sm text-gray-600 mt-2">{{ $enquiry->property->title }} • {{ $enquiry->landlord->company_name ?? $enquiry->landlord->name }} • {{ $enquiry->reference }}</p>
                            <p class="text-gray-700 mt-4">{{ $enquiry->message }}</p>
                            @if($enquiry->response)
                                <div class="mt-4 bg-gray-50 rounded-xl p-4 text-sm text-gray-700">
                                    <span class="font-semibold text-gray-900">Landlord response:</span>
                                    {{ $enquiry->response }}
                                </div>
                            @endif
                        </div>
                        <div class="flex flex-col justify-between gap-4">
                            <div class="text-sm text-gray-500">
                                Sent {{ $enquiry->created_at->format('d M Y H:i') }}
                                @if($enquiry->responded_at)
                                    <br>Responded {{ $enquiry->responded_at->format('d M Y H:i') }}
                                @endif
                            </div>
                            <a href="{{ route('student.properties.show', $enquiry->property) }}" class="inline-flex items-center justify-center border border-gray-300 text-gray-800 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Open property</a>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">No property enquiries submitted yet.</div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $enquiries->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
