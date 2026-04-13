@extends('layouts.app')

@section('title', 'Application Review')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Application Review</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

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

        <div class="mb-4">
            <a href="{{ route('welfare.applications') }}" class="text-red-800 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Back to Applications
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left: Application & Student Info -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Application Details -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Application Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Status</span>
                            <div class="mt-1">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($application->status === 'approved') bg-green-100 text-green-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($application->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Applied On</span>
                            <p class="mt-1 text-gray-900">{{ $application->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Preferred Move-in</span>
                            <p class="mt-1 text-gray-900">{{ $application->preferred_move_in_date ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Duration</span>
                            <p class="mt-1 text-gray-900">{{ $application->duration_months ?? 'N/A' }} months</p>
                        </div>
                        @if($application->special_requirements)
                        <div class="col-span-2">
                            <span class="font-medium text-gray-600">Special Requirements</span>
                            <p class="mt-1 text-gray-900">{{ $application->special_requirements }}</p>
                        </div>
                        @endif
                        @if($application->rejection_reason)
                        <div class="col-span-2">
                            <span class="font-medium text-red-600">Rejection Reason</span>
                            <p class="mt-1 text-red-700 bg-red-50 p-3 rounded">{{ $application->rejection_reason }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Student Details -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Student Details</h3>
                    </div>
                    <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-600">Name</span>
                            <p class="mt-1 text-gray-900">{{ $application->student->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Student ID</span>
                            <p class="mt-1 text-gray-900">{{ $application->student->student_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Email</span>
                            <p class="mt-1 text-gray-900">{{ $application->student->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-gray-600">Document Status</span>
                            <div class="mt-1">
                                @php $ds = $application->student->document_status ?? 'pending'; @endphp
                                <span class="px-2 py-1 rounded text-xs font-medium
                                    {{ $ds === 'verified' ? 'bg-green-100 text-green-800' : ($ds === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($ds) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Documents -->
                @if($application->student && $application->student->documents->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-red-800 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Student Documents</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($application->student->documents as $doc)
                        <div class="flex items-center justify-between border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $doc->document_type_label }}</p>
                                    <p class="text-xs text-gray-500">{{ $doc->original_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $doc->status_badge }}">
                                    {{ ucfirst($doc->status) }}
                                </span>
                                @if($doc->path)
                                    <a href="{{ route('documents.student.show', $doc) }}" target="_blank"
                                       class="text-xs text-blue-600 hover:underline">View</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Actions -->
            <div class="space-y-6">
                @if($application->status === 'pending')
                <!-- Approve -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-green-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Approve Application</h3>
                    </div>
                    <form action="{{ route('welfare.applications.approve', $application) }}" method="POST" class="p-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Assign Room <span class="text-red-600">*</span>
                            </label>
                            <select name="accommodation_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-green-600 focus:ring-green-600">
                                <option value="">Select a room…</option>
                                @foreach($availableAccommodations as $room)
                                    <option value="{{ $room->id }}">
                                        {{ $room->name }} — Block {{ $room->block }} ({{ $room->capacity - $room->current_occupancy }} spaces)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full bg-green-700 text-white py-2 rounded-lg hover:bg-green-800 transition font-medium text-sm">
                            <i class="fas fa-check mr-2"></i>Approve & Assign Room
                        </button>
                    </form>
                </div>

                <!-- Reject -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="bg-red-700 px-6 py-4">
                        <h3 class="text-lg font-semibold text-white">Reject Application</h3>
                    </div>
                    <form action="{{ route('welfare.applications.reject', $application) }}" method="POST" class="p-6">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Reason <span class="text-red-600">*</span>
                            </label>
                            <textarea name="rejection_reason" rows="4" required minlength="10"
                                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-red-600 focus:ring-red-600"
                                      placeholder="Explain the reason for rejection…"></textarea>
                        </div>
                        <button type="submit"
                                class="w-full bg-red-700 text-white py-2 rounded-lg hover:bg-red-800 transition font-medium text-sm">
                            <i class="fas fa-times mr-2"></i>Reject Application
                        </button>
                    </form>
                </div>
                @else
                <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500 text-sm">
                    This application has already been <strong>{{ $application->status }}</strong>.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
