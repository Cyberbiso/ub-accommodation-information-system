@extends('layouts.app')

@section('title', 'Verify Document')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Verify Document</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('welfare.documents.pending') }}" class="text-red-800 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Pending Documents
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Document Preview -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Document</h3>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">Type</p>
                        <p class="text-gray-900 font-semibold">{{ $document->document_type_label }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">Student</p>
                        <p class="text-gray-900">{{ $document->user->name ?? 'Unknown' }}</p>
                        <p class="text-sm text-gray-500">{{ $document->user->student_id ?? '' }} &bull; {{ $document->user->email ?? '' }}</p>
                    </div>
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-600">File</p>
                        <p class="text-gray-900 text-sm">{{ $document->original_name }}</p>
                    </div>
                    <div class="mb-6">
                        <p class="text-sm font-medium text-gray-600">Uploaded</p>
                        <p class="text-gray-900 text-sm">{{ $document->created_at->format('M d, Y H:i') }}</p>
                    </div>

                    @if($document->path)
                        @php $ext = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png']))
                            <img src="{{ Storage::url($document->path) }}" alt="Document"
                                 class="w-full rounded-lg border border-gray-200 mb-4">
                        @endif
                        <a href="{{ Storage::url($document->path) }}" target="_blank"
                           class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition text-sm">
                            <i class="fas fa-external-link-alt mr-2"></i>Open Full Document
                        </a>
                    @else
                        <div class="bg-gray-100 rounded-lg p-4 text-center text-gray-500 text-sm">
                            File not available
                        </div>
                    @endif
                </div>
            </div>

            <!-- Verification Form -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-red-800 px-6 py-4">
                    <h3 class="text-lg font-semibold text-white">Verification Decision</h3>
                </div>
                <form action="{{ route('welfare.documents.verify.process', $document) }}" method="POST" class="p-6">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Decision <span class="text-red-600">*</span></label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-400 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                                <input type="radio" name="status" value="verified" required class="text-green-600 focus:ring-green-500">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">✓ Verify</span>
                                    <p class="text-xs text-gray-500">Document is valid and accepted</p>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-red-400 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                                <input type="radio" name="status" value="rejected" class="text-red-600 focus:ring-red-500">
                                <div class="ml-3">
                                    <span class="font-medium text-gray-900">✗ Reject</span>
                                    <p class="text-xs text-gray-500">Document is invalid or insufficient</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div id="rejection-reason-field" class="mb-6 hidden">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-600">*</span>
                        </label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-red-800"
                                  placeholder="Explain why this document is being rejected…">{{ old('rejection_reason') }}</textarea>
                        @error('rejection_reason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-red-800 text-white py-3 rounded-lg hover:bg-red-900 transition font-medium">
                        Submit Decision
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="status"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        var field = document.getElementById('rejection-reason-field');
        var textarea = document.getElementById('rejection_reason');
        if (this.value === 'rejected') {
            field.classList.remove('hidden');
            textarea.setAttribute('required', 'required');
        } else {
            field.classList.add('hidden');
            textarea.removeAttribute('required');
        }
    });
});
</script>
@endsection
