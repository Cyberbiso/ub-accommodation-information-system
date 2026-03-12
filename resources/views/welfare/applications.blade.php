@extends('layouts.app')

@section('title', 'Review Applications')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Review Applications</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" action="{{ route('welfare.applications') }}" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">All Statuses</option>
                        @foreach(['pending','approved','rejected','waitlisted'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search Student</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Student name…"
                           class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-56">
                </div>
                <button type="submit" class="bg-red-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-900 transition">
                    Filter
                </button>
                <a href="{{ route('welfare.applications') }}" class="text-sm text-gray-600 hover:underline self-center">Clear</a>
            </form>
        </div>

        <!-- Applications Table -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="bg-red-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-clipboard-list mr-2"></i>Applications
                </h3>
                <span class="text-red-200 text-sm">{{ $applications->total() }} total</span>
            </div>

            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Accommodation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Applied</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($applications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900">{{ $application->student->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->student->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $application->accommodation->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $application->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($application->status === 'approved') bg-green-100 text-green-800
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                        @elseif($application->status === 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-orange-100 text-orange-800 @endif">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('welfare.applications.show', $application) }}"
                                       class="text-red-800 hover:text-red-900 font-medium text-sm">
                                        Review <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-16 text-gray-500">
                    <i class="fas fa-inbox text-5xl mb-4 text-gray-300"></i>
                    <p>No applications found.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
