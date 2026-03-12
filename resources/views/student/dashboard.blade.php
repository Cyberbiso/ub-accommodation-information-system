@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Student Dashboard
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Welcome Card with Document Status -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="text-gray-600 mt-1">Manage your accommodation applications and document verification.</p>
                    </div>

                    @php
                        $docStatus = Auth::user()->document_status;
                        $statusColors = [
                            'verified' => 'bg-green-100 text-green-800 border-green-300',
                            'rejected' => 'bg-red-100 text-red-800 border-red-300',
                            'pending'  => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        ];
                        $statusLabels = [
                            'verified' => '✓ Documents Verified',
                            'rejected' => '✗ Documents Rejected',
                            'pending'  => '⏳ Documents Under Review',
                        ];
                    @endphp

                    <span class="px-4 py-2 rounded-full text-sm font-semibold border {{ $statusColors[$docStatus] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                        {{ $statusLabels[$docStatus] ?? 'No Documents' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_applications'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Total Applications</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending_applications'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved_applications'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Approved</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['rejected_applications'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Rejected</div>
            </div>
        </div>

        <!-- Document Verification Status -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Your Documents</h3>
                <p class="text-gray-600 text-sm mt-1">Status of your uploaded documents</p>
            </div>

            <div class="p-6">
                @php
                    $user      = Auth::user();
                    $documents = $user->documents()->get();
                @endphp

                @if($documents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($documents as $document)
                            @php
                                $cardColors = [
                                    'verified' => 'bg-green-50 border-green-300',
                                    'rejected' => 'bg-red-50 border-red-300',
                                    'pending'  => 'bg-yellow-50 border-yellow-300',
                                ];
                                $icons = ['verified' => '✓', 'rejected' => '✗', 'pending' => '⏳'];
                                $textColors = [
                                    'verified' => 'text-green-600',
                                    'rejected' => 'text-red-600',
                                    'pending'  => 'text-yellow-600',
                                ];
                                $labels = [
                                    'acceptance_letter'    => 'Acceptance Letter',
                                    'proof_of_registration'=> 'Proof of Registration',
                                    'passport'             => 'Passport Copy',
                                ];
                            @endphp

                            <div class="border-2 rounded-lg p-5 {{ $cardColors[$document->status] ?? 'bg-gray-50 border-gray-300' }}">
                                <div class="flex items-start justify-between mb-3">
                                    <h4 class="font-bold text-gray-900">
                                        {{ $labels[$document->document_type] ?? ucfirst(str_replace('_',' ',$document->document_type)) }}
                                    </h4>
                                    <span class="text-xl">{{ $icons[$document->status] ?? '📄' }}</span>
                                </div>

                                <div class="space-y-2">
                                    <span class="text-sm font-medium {{ $textColors[$document->status] ?? 'text-gray-600' }}">
                                        {{ ucfirst($document->status === 'pending' ? 'Under Review' : $document->status) }}
                                    </span>

                                    <div class="bg-white rounded-lg p-2 border border-gray-200">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                            <span class="truncate" title="{{ $document->original_name }}">
                                                {{ $document->original_name }}
                                            </span>
                                        </div>
                                    </div>

                                    @if($document->status === 'rejected' && $document->rejection_reason)
                                        <button onclick="showRejectionReason('{{ addslashes($document->rejection_reason) }}')"
                                                class="text-xs text-red-600 hover:underline mt-2">
                                            View Rejection Reason →
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($user->document_status === 'rejected')
                        <div class="mt-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg">
                            <p class="text-sm text-red-700">
                                <span class="font-semibold">Document Verification Failed:</span>
                                Some documents were rejected. Please contact the Welfare Office.
                            </p>
                        </div>
                    @elseif($user->document_status === 'pending')
                        <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <p class="text-sm text-blue-700">
                                <span class="font-semibold">Documents Under Review:</span>
                                Your documents are being verified by the Welfare Office (1–2 business days).
                            </p>
                        </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4"><i class="fas fa-file-upload"></i></div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">No Documents Uploaded</h4>
                        <p class="text-gray-500">Please upload your required documents during registration.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <a href="{{ route('student.apply.form') }}"
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Apply for On-Campus Housing</h3>
                    <p class="text-gray-600 text-sm">Submit your accommodation application</p>
                </div>
                <i class="fas fa-arrow-right text-red-800 text-2xl group-hover:translate-x-2 transition"></i>
            </a>

            <a href="{{ route('student.properties') }}"
               class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition flex items-center justify-between group">
                <div>
                    <h3 class="font-semibold text-lg text-gray-900">Browse Off-Campus</h3>
                    <p class="text-gray-600 text-sm">Find private properties near campus</p>
                </div>
                <i class="fas fa-arrow-right text-red-800 text-2xl group-hover:translate-x-2 transition"></i>
            </a>
        </div>

        <!-- Recent Applications -->
        @if($recentApplications->count() > 0)
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-900">Recent Applications</h3>
                <a href="{{ route('student.applications') }}" class="text-sm text-red-800 hover:underline">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentApplications as $application)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $application->accommodation->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $application->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($application->status === 'approved') bg-green-100 text-green-800
                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                        @elseif($application->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-900">Rejection Reason</h3>
            <button onclick="closeRejectionModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p id="rejectionReason" class="text-gray-700 mb-6 bg-gray-50 p-4 rounded-lg"></p>
        <div class="flex justify-end">
            <button onclick="closeRejectionModal()" class="bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function showRejectionReason(reason) {
        document.getElementById('rejectionReason').textContent = reason;
        const modal = document.getElementById('rejectionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeRejectionModal() {
        const modal = document.getElementById('rejectionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endsection
