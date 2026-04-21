@extends('layouts.public')

@section('title', 'Information Hub - Student Onboarding Guide')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Information Hub</h1>
                <p class="text-xl mb-6 max-w-2xl">
                    Everything you need to know before and after arrival – campus offices, immigration guides, and resources.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#quick-links" class="bg-white text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-compass mr-2"></i>Explore
                    </a>
                    <a href="#resources" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                        <i class="fas fa-download mr-2"></i>Resources
                    </a>
                </div>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="{{ url('/') }}" class="text-white hover:underline flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['offices'] }}+</div>
                <div class="text-gray-600">Campus Offices</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['requirements'] }}+</div>
                <div class="text-gray-600">Immigration Guides</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['resources'] }}+</div>
                <div class="text-gray-600">Resources</div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links Grid -->
<div id="quick-links" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">Quick Access</h2>
        <p class="text-lg text-gray-600 mb-12 text-center max-w-3xl mx-auto">
            Find what you need quickly with our organized information sections.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Campus Directory Card -->
            <a href="{{ route('information.campus-directory') }}" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition group">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-4xl mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Campus Directory</h3>
                    <p class="text-gray-600 mb-4">Find offices, departments, and key locations on campus.</p>
                    <span class="text-red-800 font-medium inline-flex items-center">
                        Explore <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </span>
                </div>
            </a>

            <!-- Immigration Card -->
            <a href="{{ route('information.immigration') }}" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition group">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-4xl mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-passport"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Immigration</h3>
                    <p class="text-gray-600 mb-4">Visa requirements, study permits, and important deadlines for international students.</p>
                    <span class="text-red-800 font-medium inline-flex items-center">
                        Learn More <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </span>
                </div>
            </a>

            <!-- Resources Card -->
            <a href="{{ route('information.resources') }}" class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition group">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-4xl mb-4 group-hover:scale-110 transition">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Resources</h3>
                    <p class="text-gray-600 mb-4">Downloadable guides, forms, and helpful links for new and returning students.</p>
                    <span class="text-red-800 font-medium inline-flex items-center">
                        Browse Resources <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Featured Offices -->
@if($featuredOffices->count() > 0)
<div class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Key Campus Offices</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredOffices as $office)
            <div class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                <h3 class="font-semibold text-lg mb-2">{{ $office->office_name }}</h3>
                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($office->description, 100) }}</p>
                <div class="space-y-2 text-sm">
                    <p><i class="fas fa-map-marker-alt text-red-800 mr-2 w-4"></i> {{ $office->building }}</p>
                    @if($office->phone)
                    <p><i class="fas fa-phone text-red-800 mr-2 w-4"></i> {{ $office->phone }}</p>
                    @endif
                    @if($office->hours)
                    <p><i class="fas fa-clock text-red-800 mr-2 w-4"></i> {{ $office->hours }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Urgent Deadlines -->
@if($urgentDeadlines->count() > 0)
<div class="py-12 bg-yellow-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold mb-4 flex items-center text-yellow-800">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Important Deadlines
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($urgentDeadlines as $deadline)
            <div class="bg-white p-4 rounded-lg border-l-4 border-yellow-500 shadow">
                <h3 class="font-semibold">{{ $deadline->title }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ Str::limit($deadline->description, 80) }}</p>
                @if($deadline->deadline)
                <p class="text-sm text-red-600 font-medium mt-2">
                    <i class="fas fa-calendar-alt mr-1"></i> 
                    Due: {{ \Carbon\Carbon::parse($deadline->deadline)->format('M d, Y') }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Recent Resources -->
<div id="resources" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Latest Resources</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($recentResources as $resource)
            @php
                $resourceUrl = ($resource->file_path || $resource->external_link)
                    ? route('information.resources.download', $resource)
                    : null;
                $resourceActionLabel = in_array($resource->type, ['link', 'video'], true) ? 'Open Resource' : 'Download';
            @endphp
            <div class="bg-gray-50 rounded-lg p-6 hover:shadow-md transition">
                <div class="text-3xl text-red-800 mb-3">
                    @if($resource->type == 'document')
                        <i class="fas fa-file-pdf"></i>
                    @elseif($resource->type == 'video')
                        <i class="fas fa-video"></i>
                    @elseif($resource->type == 'link')
                        <i class="fas fa-link"></i>
                    @else
                        <i class="fas fa-file"></i>
                    @endif
                </div>
                <h3 class="font-semibold mb-1">{{ $resource->title }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($resource->description, 60) }}</p>
                @if($resourceUrl)
                    <a href="{{ $resourceUrl }}" class="text-red-800 hover:text-red-900 text-sm font-medium">
                        {{ $resourceActionLabel }} <i class="fas fa-arrow-up-right-from-square ml-1"></i>
                    </a>
                @else
                    <p class="text-sm text-gray-400">Resource file not available yet.</p>
                @endif
            </div>
            @empty
            <div class="col-span-4 text-center py-8 text-gray-500">
                No resources available yet.
            </div>
            @endforelse
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('information.resources') }}" class="inline-block bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900 transition">
                View All Resources
            </a>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Begin Your Journey?</h2>
        <p class="text-xl mb-8 opacity-90">Get all the information you need for a smooth transition to university life.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-red-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </a>
            <a href="{{ route('accommodation.hub') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                <i class="fas fa-home mr-2"></i>Find Accommodation
            </a>
        </div>
    </div>
</div>
@endsection
