@extends('layouts.app')

@section('title', 'Student Home')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Student Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white rounded-lg shadow-lg mb-6">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-red-100 mt-1">Track your applications and stay updated.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <!-- Document Status Alert -->
        @if(Auth::user()->document_status === 'pending' && Auth::user()->documents->count() > 0)
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <span class="font-semibold">Documents Under Review:</span>
                            Your documents are being verified by the Welfare Office.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Grid - 2 columns -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Applications (2/3 width) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- On-Campus Applications -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-building mr-2"></i>
                            On-Campus Applications
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($onCampusApplications->count() > 0)
                            <div class="space-y-4">
                                @foreach($onCampusApplications as $application)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="font-bold text-gray-900">Application #{{ $application->id }}</h3>
                                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                                @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($application->status == 'approved') bg-green-100 text-green-800
                                                @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-gray-500">Applied:</span>
                                                <p class="font-medium">{{ $application->created_at->format('d M Y') }}</p>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Room:</span>
                                                <p class="font-medium">{{ $application->accommodation->name ?? 'Pending' }}</p>
                                            </div>
                                        </div>
                                        
                                        @if($application->status == 'pending')
                                            <div class="mt-3 p-2 bg-yellow-50 text-yellow-700 text-sm rounded">
                                                <i class="fas fa-clock mr-1"></i> Awaiting review
                                            </div>
                                        @elseif($application->status == 'approved')
                                            <div class="mt-3 p-2 bg-green-50 text-green-700 text-sm rounded">
                                                <i class="fas fa-check-circle mr-1"></i> Approved
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-600 mb-4">No applications yet</p>
                                <a href="{{ route('student.apply.form') }}" 
                                   class="inline-block bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                    Apply Now
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Off-Campus Viewings -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            Off-Campus Viewings
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($viewingRequests->count() > 0)
                            <div class="space-y-4">
                                @foreach($viewingRequests as $request)
                                    <div class="border rounded-lg p-4 hover:shadow-md transition">
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-bold text-gray-900">{{ $request->property->title ?? 'Property' }}</h3>
                                            <span class="px-3 py-1 rounded-full text-xs
                                                @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($request->status == 'approved') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($request->status) }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">Requested: {{ $request->created_at->format('d M Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600 text-center py-4">No viewing requests</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="space-y-6">
                <!-- Information Hub -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Information Hub
                        </h2>
                    </div>
                    <div class="p-4">
                        <a href="{{ route('information.campus-directory') }}" 
                           class="block p-3 bg-gray-50 rounded-lg mb-2 hover:bg-gray-100 transition">
                            <i class="fas fa-map-marked-alt text-red-800 mr-2"></i> Campus Directory
                        </a>
                        <a href="{{ route('information.immigration') }}" 
                           class="block p-3 bg-gray-50 rounded-lg mb-2 hover:bg-gray-100 transition">
                            <i class="fas fa-passport text-red-800 mr-2"></i> Immigration
                        </a>
                        <a href="{{ route('information.checklist') }}" 
                           class="block p-3 bg-gray-50 rounded-lg mb-2 hover:bg-gray-100 transition">
                            <i class="fas fa-clipboard-list text-red-800 mr-2"></i> Checklist
                        </a>
                        <a href="{{ route('information.resources') }}" 
                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <i class="fas fa-book-open text-red-800 mr-2"></i> Resources
                        </a>
                    </div>
                </div>

                <!-- Notice Board -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Notice Board
                        </h2>
                    </div>
                    <div class="p-4">
                        @if($onCampusApplications->where('status', 'pending')->count() > 0)
                            <div class="p-3 bg-yellow-50 rounded-lg mb-2">
                                <p class="text-sm">Your application is pending review</p>
                                <p class="text-xs text-gray-500">Just now</p>
                            </div>
                        @endif
                        <div class="text-right mt-2">
                            <a href="{{ route('student.applications') }}" class="text-sm text-red-800 hover:underline">
                                View all →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h2 class="text-lg font-semibold text-white flex items-center">
                            <i class="fas fa-chart-pie mr-2"></i>
                            Quick Stats
                        </h2>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-red-800">{{ $onCampusApplications->count() }}</div>
                                <div class="text-xs text-gray-600">Applications</div>
                            </div>
                            <div class="text-center p-3 bg-gray-50 rounded-lg">
                                <div class="text-xl font-bold text-red-800">{{ $viewingRequests->count() }}</div>
                                <div class="text-xs text-gray-600">Viewings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
