@extends('layouts.app')

@section('title', 'Pending Document Verification')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Document Verification</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-r-lg">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_pending'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Pending</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-green-600">{{ $stats['total_verified'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Verified</div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 text-center">
                <div class="text-2xl font-bold text-red-600">{{ $stats['total_rejected'] }}</div>
                <div class="text-xs text-gray-600 mt-1">Rejected</div>
            </div>
        </div>

        <!-- Pending Documents List -->
        <div class="bg-white overflow-hidden shadow-xl rounded-lg">
            <div class="bg-red-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">
                    <i class="fas fa-file-pdf mr-2"></i>Pending Documents
                </h3>
            </div>

            @if($pendingDocuments->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($pendingDocuments as $doc)
                    <div class="p-6 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-pdf text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $doc->document_type_label }}</p>
                                <p class="text-sm text-gray-600">
                                    Student: <span class="font-medium">{{ $doc->user->name ?? 'Unknown' }}</span>
                                    ({{ $doc->user->student_id ?? 'N/A' }})
                                </p>
                                <p class="text-xs text-gray-500">
                                    File: {{ $doc->original_name }} &bull;
                                    Uploaded {{ $doc->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            @if($doc->path)
                                <a href="{{ route('documents.student.show', $doc) }}" target="_blank"
                                   class="text-sm text-blue-600 hover:underline border border-blue-300 px-3 py-1 rounded">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                            @endif
                            <a href="{{ route('welfare.documents.verify', $doc) }}"
                               class="bg-red-800 text-white text-sm px-4 py-2 rounded-lg hover:bg-red-900 transition">
                                Verify
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pendingDocuments->links() }}
                </div>
            @else
                <div class="text-center py-16 text-gray-500">
                    <i class="fas fa-check-circle text-5xl mb-4 text-green-300"></i>
                    <p class="font-medium">All documents are verified!</p>
                    <p class="text-sm">No pending documents at this time.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
