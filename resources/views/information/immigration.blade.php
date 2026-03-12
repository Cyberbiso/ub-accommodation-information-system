@extends('layouts.public')

@section('title', 'Immigration Compliance')

@section('content')
<div class="bg-gradient-to-r from-red-800 to-red-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">Immigration Compliance</h1>
        <p class="text-lg opacity-90">Visa requirements, permits, and important deadlines</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Category Filter -->
    <div class="mb-8">
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('information.immigration') }}" 
               class="px-4 py-2 rounded-full {{ !request('category') ? 'bg-red-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('information.immigration', ['category' => $cat]) }}" 
               class="px-4 py-2 rounded-full {{ request('category') == $cat ? 'bg-red-800 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ ucfirst($cat) }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Requirements List -->
    <div class="space-y-6">
        @forelse($requirements as $req)
        <div class="border rounded-lg p-6 {{ $req->priority == 1 ? 'border-l-4 border-l-red-600' : '' }}">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-xl font-semibold">{{ $req->title }}</h3>
                    <p class="text-gray-600 mt-2">{{ $req->description }}</p>
                </div>
                <span class="{{ $req->priority_badge }} px-3 py-1 rounded-full text-sm">
                    @if($req->priority == 1) High Priority
                    @elseif($req->priority == 2) Medium
                    @else Low
                    @endif
                </span>
            </div>

            @if($req->required_documents)
            <div class="mt-4">
                <h4 class="font-medium mb-2">Required Documents:</h4>
                <ul class="list-disc list-inside space-y-1 text-gray-600">
                    @foreach(json_decode($req->required_documents) as $doc)
                    <li>{{ $doc }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($req->process_steps)
            <div class="mt-4">
                <h4 class="font-medium mb-2">Process:</h4>
                <pre class="whitespace-pre-wrap text-gray-600">{{ $req->process_steps }}</pre>
            </div>
            @endif

            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                @if($req->office_responsible)
                <span><i class="fas fa-building mr-1"></i> {{ $req->office_responsible }}</span>
                @endif
                @if($req->deadline)
                <span><i class="fas fa-calendar-alt mr-1"></i> Deadline: {{ $req->deadline->format('M d, Y') }}</span>
                @endif
            </div>

            @if($req->link_to_form)
            <div class="mt-4">
                <a href="{{ $req->link_to_form }}" class="text-red-800 hover:text-red-900 font-medium">
                    <i class="fas fa-download mr-1"></i> Download Form
                </a>
            </div>
            @endif
        </div>
        @empty
        <p class="text-center text-gray-500 py-12">No requirements found.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $requirements->links() }}
    </div>
</div>
@endsection
