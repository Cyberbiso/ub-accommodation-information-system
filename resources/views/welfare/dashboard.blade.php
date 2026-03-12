@extends('layouts.app')

@section('title', 'Welfare Officer Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Welfare Officer Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white rounded-lg shadow-lg mb-6">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold">Welcome, {{ Auth::user()->name }}!</h1>
                <p class="text-red-100 mt-1">Manage student applications, accommodations, and occupancy reports.</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Applications</p>
                        <p class="text-2xl font-bold">{{ $stats['total_applications'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold">{{ $stats['pending_applications'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Approved</p>
                        <p class="text-2xl font-bold">{{ $stats['approved_applications'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3 mr-4">
                        <i class="fas fa-bed text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Available Rooms</p>
                        <p class="text-2xl font-bold">{{ $stats['available_rooms'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Rejected</span>
                    <span class="text-lg font-bold text-red-600">{{ $stats['rejected_applications'] ?? 0 }}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Waitlisted</span>
                    <span class="text-lg font-bold text-orange-600">{{ $stats['waitlisted_applications'] ?? 0 }}</span>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Occupancy Rate</span>
                    <span class="text-lg font-bold text-green-600">{{ $stats['occupancy_rate'] ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('welfare.applications', ['status' => 'pending']) }}" 
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-2 border-gray-100 hover:border-red-800 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-clipboard-check text-red-800 text-2xl mr-3"></i>
                            <h3 class="font-bold text-lg text-gray-900">Review Applications</h3>
                        </div>
                        <p class="text-gray-600 ml-11">{{ $stats['pending_applications'] ?? 0 }} pending reviews</p>
                    </div>
                    <i class="fas fa-arrow-right text-red-800 text-2xl group-hover:translate-x-2 transition"></i>
                </div>
            </a>
            
            <a href="{{ route('welfare.accommodations') }}" 
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-2 border-gray-100 hover:border-red-800 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-building text-red-800 text-2xl mr-3"></i>
                            <h3 class="font-bold text-lg text-gray-900">Manage Accommodations</h3>
                        </div>
                        <p class="text-gray-600 ml-11">{{ $stats['total_accommodations'] ?? 0 }} total rooms</p>
                    </div>
                    <i class="fas fa-arrow-right text-red-800 text-2xl group-hover:translate-x-2 transition"></i>
                </div>
            </a>
            
            <a href="{{ route('welfare.accommodations.create') }}" 
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-2 border-gray-100 hover:border-red-800 group">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <i class="fas fa-plus-circle text-red-800 text-2xl mr-3"></i>
                            <h3 class="font-bold text-lg text-gray-900">Add New Room</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Create new accommodation</p>
                    </div>
                    <i class="fas fa-arrow-right text-red-800 text-2xl group-hover:translate-x-2 transition"></i>
                </div>
            </a>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Applications -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="bg-red-800 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>
                        Recent Applications
                    </h3>
                    <a href="{{ route('welfare.applications') }}" class="text-white hover:text-red-200 text-sm">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($recentApplications) && $recentApplications->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentApplications as $application)
                                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0 last:pb-0">
                                    <div class="flex items-start">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $application->student->name ?? 'Unknown' }}</p>
                                            <p class="text-sm text-gray-600">{{ $application->accommodation->name ?? 'No room assigned' }}</p>
                                            <p class="text-xs text-gray-500">Applied: {{ $application->created_at->format('M d, Y') }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 rounded-full text-xs font-medium
                                            @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($application->status == 'approved') bg-green-100 text-green-800
                                            @elseif($application->status == 'rejected') bg-red-100 text-red-800
                                            @elseif($application->status == 'waitlisted') bg-orange-100 text-orange-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No recent applications</p>
                    @endif
                </div>
            </div>

            <!-- Occupancy Overview -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="bg-red-800 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-pie mr-2"></i>
                        Occupancy Overview
                    </h3>
                    <a href="{{ route('welfare.occupancy.overview') }}" class="text-white hover:text-red-200 text-sm">
                        View Details <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($occupancyData) && $occupancyData->count() > 0)
                        @foreach($occupancyData as $block => $rooms)
                            <div class="mb-4 last:mb-0">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-900">Block {{ $block }}</h4>
                                    <span class="text-sm text-gray-600">
                                        {{ $rooms->sum('current_occupancy') }}/{{ $rooms->sum('capacity') }} occupied
                                    </span>
                                </div>
                                @foreach($rooms->take(2) as $room)
                                    <div class="mb-2">
                                        <div class="flex justify-between items-center text-sm mb-1">
                                            <span class="text-gray-600">{{ $room->name }}</span>
                                            <span class="text-gray-500">{{ $room->current_occupancy }}/{{ $room->capacity }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-1.5">
                                            <div class="bg-red-800 h-1.5 rounded-full" 
                                                 style="width: {{ ($room->current_occupancy / $room->capacity) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($rooms->count() > 2)
                                    <div class="text-center mt-1">
                                        <a href="{{ route('welfare.accommodations', ['block' => $block]) }}" 
                                           class="text-xs text-red-800 hover:underline">
                                            + {{ $rooms->count() - 2 }} more rooms
                                        </a>
                                    </div>
                                @endif
                            </div>
                            @if(!$loop->last)
                                <hr class="my-4">
                            @endif
                        @endforeach
                    @else
                        <p class="text-gray-500 text-center py-4">No occupancy data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Features Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Document Verification Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="bg-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Document Verification
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $pendingDocuments = App\Models\User::where('role', 'student')
                            ->where('document_status', 'pending')
                            ->count();
                    @endphp
                    <p class="text-3xl font-bold text-gray-900 mb-2">{{ $pendingDocuments }}</p>
                    <p class="text-gray-600 mb-4">Pending document verifications</p>
                    <a href="{{ route('welfare.documents.pending') }}" 
                       class="inline-flex items-center text-red-800 hover:text-red-900 font-medium">
                        Review Documents <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>

            <!-- Reports Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="bg-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Reports
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <a href="{{ route('welfare.reports.occupancy') }}" 
                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-building text-red-800 mr-3"></i>
                                <span>Occupancy Report</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </div>
                        </a>
                        <a href="{{ route('welfare.applications.overview') }}" 
                           class="block p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center">
                                <i class="fas fa-file-alt text-red-800 mr-3"></i>
                                <span>Applications Overview</span>
                                <i class="fas fa-arrow-right ml-auto text-gray-400"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-lg">
                <div class="bg-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>
                        System Overview
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-800">{{ $stats['total_students'] ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Total Students</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-800">{{ $stats['housed_students'] ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Housed Students</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['available_rooms'] ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Available Rooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_accommodations'] ?? 0 }}</div>
                            <div class="text-xs text-gray-600">Total Rooms</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection