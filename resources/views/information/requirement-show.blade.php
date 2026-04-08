@extends('layouts.public')

@section('title', $requirement->title)

@section('content')
<div class="hero-gradient text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">{{ $requirement->title }}</h1>
        <p class="text-lg opacity-90">{{ ucfirst(str_replace('_', ' ', $requirement->category)) }}</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow p-8">
        <div class="flex items-center justify-between gap-4 flex-wrap">
            <p class="text-gray-700 text-lg max-w-3xl">{{ $requirement->description }}</p>
            <span class="{{ $requirement->priority_badge }} px-3 py-2 rounded-full text-sm font-semibold">
                @if($requirement->priority == 1) High Priority
                @elseif($requirement->priority == 2) Medium Priority
                @else Low Priority
                @endif
            </span>
        </div>

        @if($requirement->required_documents)
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">Required Documents</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-700">
                    @foreach($requirement->required_documents ?? [] as $document)
                        <li>{{ $document }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($requirement->process_steps)
            <div class="mt-8">
                <h2 class="text-xl font-bold text-gray-900 mb-3">Process Steps</h2>
                <pre class="whitespace-pre-wrap text-gray-700 bg-gray-50 rounded-xl p-4">{{ $requirement->process_steps }}</pre>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
            @if($requirement->office_responsible)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Office Responsible</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $requirement->office_responsible }}</p>
                </div>
            @endif
            @if($requirement->deadline)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Deadline</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $requirement->deadline->format('M d, Y') }}</p>
                </div>
            @endif
        </div>

        @if($requirement->link_to_form)
            <div class="mt-8">
                <a href="{{ $requirement->link_to_form }}" class="inline-flex items-center bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                    <i class="fas fa-download mr-2"></i>Open Form
                </a>
            </div>
        @endif

        <div class="mt-8">
            <a href="{{ route('information.immigration') }}" class="inline-flex items-center text-red-800 font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Immigration Guidance
            </a>
        </div>
    </div>
</div>
@endsection
