@extends('layouts.app')

@section('title', 'My Applications')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        My Applications
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Your Applications</h3>
                
                @if(isset($applications) && $applications->count() > 0)
                    <div class="space-y-4">
                        @foreach($applications as $application)
                            <div class="border rounded-lg p-4">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h4 class="font-bold">{{ $application->accommodation->name }}</h4>
                                        <p class="text-sm text-gray-600">Applied: {{ $application->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm
                                        @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($application->status == 'approved') bg-green-100 text-green-800
                                        @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-8">You haven't submitted any applications yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection