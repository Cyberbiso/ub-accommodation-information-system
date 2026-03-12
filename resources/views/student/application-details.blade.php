@extends('layouts.app')

@section('title', 'Application Details')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Application Details
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('student.applications') }}" class="text-red-800 hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Applications
            </a>
        </div>

        <!-- Application Status Banner -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">Application {{ $application->application_reference }}</h1>
                        <p class="text-gray-600">Submitted on {{ $application->created_at->format('l, d M Y h:i A') }}</p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-medium
                        @if($application->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($application->status == 'approved') bg-green-100 text-green-800
                        @elseif($application->status == 'rejected') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Content - Application Details -->
            <div class="md:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Personal Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Full Name</p>
                            <p class="font-medium">{{ $application->student->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Student ID</p>
                            <p class="font-medium">{{ $application->student->student_id ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-medium">{{ $application->student->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{ $application->student->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Application Details -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Application Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Preferred Accommodation</p>
                            <p class="font-medium">{{ $application->accommodation->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Room Type</p>
                            <p class="font-medium capitalize">{{ $application->accommodation->type }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Monthly Rent</p>
                            <p class="font-medium">P{{ number_format($application->accommodation->monthly_rent, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Lease Term</p>
                            <p class="font-medium">{{ $application->duration_months }} months</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Preferred Move-in Date</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($application->preferred_move_in_date)->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if($application->special_requirements)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-2">Special Requirements</p>
                            <p class="bg-gray-50 p-3 rounded-lg">{{ $application->special_requirements }}</p>
                        </div>
                    @endif
                </div>

                <!-- Emergency Contact -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Emergency Contact</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $application->emergency_contact_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Relationship</p>
                            <p class="font-medium">{{ $application->emergency_contact_relationship }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Phone</p>
                            <p class="font-medium">{{ $application->emergency_contact_phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                @if($application->documents)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Supporting Documents</h2>
                        <div class="space-y-2">
                            @foreach(json_decode($application->documents) ?? [] as $document)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-800 mr-2"></i>
                                        <span>{{ $document->name }}</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $document->path) }}" 
                                       target="_blank"
                                       class="text-red-800 hover:underline text-sm">
                                        <i class="fas fa-download mr-1"></i>Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Application Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-check text-green-600 text-sm"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Application Submitted</p>
                                <p class="text-xs text-gray-500">{{ $application->created_at->format('d M Y h:i A') }}</p>
                            </div>
                        </div>

                        @if($application->status == 'approved')
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-check text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Application Approved</p>
                                    <p class="text-xs text-gray-500">{{ $application->approved_at->format('d M Y h:i A') }}</p>
                                </div>
                            </div>
                        @endif

                        @if($application->status == 'rejected')
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-times text-red-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">Application Rejected</p>
                                    <p class="text-xs text-gray-500">{{ $application->updated_at->format('d M Y h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                @if($application->payment)
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">Payment Status</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Application Fee</span>
                                <span class="font-bold">P{{ number_format($application->payment->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Status</span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($application->payment->status == 'completed') bg-green-100 text-green-800
                                    @elseif($application->payment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($application->payment->status) }}
                                </span>
                            </div>
                            @if($application->payment->status == 'completed')
                                <div class="text-xs text-gray-500">
                                    Paid on {{ $application->payment->paid_at->format('d M Y') }}
                                </div>
                                <a href="{{ route('student.payments.show', $application->payment) }}" 
                                   class="block text-center text-sm text-red-800 hover:underline mt-2">
                                    View Receipt
                                </a>
                            @elseif($application->payment->status == 'pending')
                                <a href="{{ route('student.payments.show', $application->payment) }}" 
                                   class="block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition text-sm">
                                    Pay Now
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Need Help? -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-2">Need Help?</h2>
                    <p class="text-sm text-gray-600 mb-4">
                        If you have questions about your application, please contact the Student Welfare Office.
                    </p>
                    <div class="space-y-2 text-sm">
                        <p><i class="fas fa-phone text-red-800 mr-2"></i> +267 123 4567</p>
                        <p><i class="fas fa-envelope text-red-800 mr-2"></i> welfare@ub.ac.bw</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
