@extends('layouts.app')

@section('title', 'My Applications')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">My Applications</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">On-campus accommodation applications</h1>
                    <p class="text-gray-600 mt-1">Track room allocation decisions and related payments.</p>
                </div>
                <a href="{{ route('student.apply.form') }}" class="bg-red-800 text-white px-4 py-3 rounded-lg font-semibold hover:bg-red-900 transition">New application</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($applications as $application)
                    <div class="p-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-900">{{ $application->accommodation->name ?? 'General allocation request' }}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">Submitted {{ $application->created_at->format('d M Y') }}</p>
                            <p class="text-sm text-gray-600 mt-1">Move in: {{ optional($application->preferred_move_in_date)->format('d M Y') ?? 'TBD' }} • Duration: {{ $application->duration_months }} months</p>
                            @if($application->special_requirements)
                                <p class="text-sm text-gray-600 mt-2">{{ $application->special_requirements }}</p>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('student.applications.show', $application) }}" class="border border-gray-300 text-gray-800 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition">View details</a>
                            @if($application->payment && $application->payment->status === 'pending')
                                <a href="{{ route('student.payments') }}" class="bg-red-800 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-900 transition">Pay fee</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <i class="fas fa-file-signature text-5xl text-gray-300"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mt-4">No applications yet</h3>
                        <p class="text-gray-600 mt-2">Submit your first on-campus application to start room allocation.</p>
                    </div>
                @endforelse
            </div>

            <div class="px-6 py-4 border-t border-gray-100">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
