@extends('layouts.app')

@section('title', 'My Viewing Requests')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        My Viewing Requests
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Tabs -->
        <div class="bg-white rounded-t-lg shadow overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px">
                    <a href="{{ route('student.viewing-requests') }}" 
                       class="px-6 py-3 text-sm font-medium {{ !request('status') ? 'border-b-2 border-red-800 text-red-800' : 'text-gray-500 hover:text-gray-700' }}">
                        All Requests
                    </a>
                    <a href="{{ route('student.viewing-requests', ['status' => 'pending']) }}" 
                       class="px-6 py-3 text-sm font-medium {{ request('status') == 'pending' ? 'border-b-2 border-red-800 text-red-800' : 'text-gray-500 hover:text-gray-700' }}">
                        Pending
                    </a>
                    <a href="{{ route('student.viewing-requests', ['status' => 'approved']) }}" 
                       class="px-6 py-3 text-sm font-medium {{ request('status') == 'approved' ? 'border-b-2 border-red-800 text-red-800' : 'text-gray-500 hover:text-gray-700' }}">
                        Approved
                    </a>
                    <a href="{{ route('student.viewing-requests', ['status' => 'completed']) }}" 
                       class="px-6 py-3 text-sm font-medium {{ request('status') == 'completed' ? 'border-b-2 border-red-800 text-red-800' : 'text-gray-500 hover:text-gray-700' }}">
                        Completed
                    </a>
                </nav>
            </div>
        </div>

        <!-- Requests List -->
        <div class="bg-white rounded-b-lg shadow p-6">
            @forelse($viewingRequests as $request)
                <div class="border rounded-lg p-6 mb-4 last:mb-0 hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-start">
                                <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden mr-4">
                                    @if($request->property->photos && count($request->property->photos) > 0)
                                        <img src="{{ asset('storage/' . $request->property->photos[0]) }}" 
                                             alt="{{ $request->property->title }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                            <i class="fas fa-home text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="font-bold text-lg">{{ $request->property->title }}</h3>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($request->status == 'approved') bg-green-100 text-green-800
                                            @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                            @elseif($request->status == 'completed') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-3">
                                        <div>
                                            <span class="text-gray-500 text-xs">Requested:</span>
                                            <p class="font-medium">{{ $request->created_at->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 text-xs">Preferred:</span>
                                            <p class="font-medium">{{ $request->preferred_date->format('d M Y') }} at {{ $request->preferred_time }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 text-xs">Location:</span>
                                            <p class="font-medium">{{ $request->property->city }}</p>
                                        </div>
                                        <div>
                                            <span class="text-gray-500 text-xs">Rent:</span>
                                            <p class="font-medium">P{{ number_format($request->property->monthly_rent, 2) }}</p>
                                        </div>
                                    </div>

                                    @if($request->status == 'approved' && $request->scheduled_date)
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            <p class="text-sm text-green-800">
                                                <i class="fas fa-check-circle mr-2"></i>
                                                <span class="font-medium">Scheduled:</span>
                                                {{ \Carbon\Carbon::parse($request->scheduled_date)->format('l, d M Y') }} 
                                                at {{ $request->scheduled_time }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($request->status == 'rejected' && $request->landlord_response)
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-red-800">
                                                <i class="fas fa-exclamation-circle mr-2"></i>
                                                <span class="font-medium">Response:</span>
                                                {{ $request->landlord_response }}
                                            </p>
                                        </div>
                                    @endif

                                    @if($request->message)
                                        <div class="mt-2 text-sm text-gray-600">
                                            <span class="font-medium">Your message:</span>
                                            <p>{{ $request->message }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($request->status == 'pending')
                            <div class="mt-4 md:mt-0 md:ml-4">
                                <a href="{{ route('student.viewing-requests.cancel', $request) }}" 
                                   class="text-sm text-red-600 hover:underline"
                                   onclick="return confirm('Are you sure you want to cancel this request?')">
                                    Cancel Request
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No Viewing Requests</h3>
                    <p class="text-gray-500 mb-4">You haven't made any viewing requests yet.</p>
                    <a href="{{ route('student.off-campus') }}" class="inline-block bg-red-800 text-white px-6 py-3 rounded-lg hover:bg-red-900 transition">
                        Browse Properties
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-6">
                {{ $viewingRequests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection