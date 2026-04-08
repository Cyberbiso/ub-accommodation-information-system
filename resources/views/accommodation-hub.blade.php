@extends('layouts.public')

@section('title', 'Accommodation Hub - Find Your Student Home')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Accommodation Hub</h1>
                <p class="text-xl mb-6 max-w-2xl">
                    Find your perfect student home with on-campus housing or off-campus properties.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#on-campus" class="bg-white text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        <i class="fas fa-building mr-2"></i>On-Campus
                    </a>
                    <a href="#off-campus" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                        <i class="fas fa-search mr-2"></i>Off-Campus
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
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['on_campus'] }}+</div>
                <div class="text-gray-600">On-Campus Rooms</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['off_campus'] }}+</div>
                <div class="text-gray-600">Off-Campus Properties</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['landlords'] }}+</div>
                <div class="text-gray-600">Trusted Landlords</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ $stats['viewings'] }}+</div>
                <div class="text-gray-600">Viewings This Month</div>
            </div>
        </div>
    </div>
</div>

<!-- On-Campus Section -->
<div id="on-campus" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">
            <i class="fas fa-building text-red-800 mr-3"></i>
            On-Campus Housing
        </h2>
        <p class="text-lg text-gray-600 mb-8 max-w-3xl">
            Live on campus and be at the heart of university life. Apply for university-managed accommodation directly through our platform.
        </p>

        @if($featuredAccommodations->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($featuredAccommodations as $accommodation)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $accommodation->name }}</h3>
                    <div class="flex items-center text-gray-600 mb-3">
                        <i class="fas fa-tag text-red-800 mr-2"></i>
                        <span>P{{ number_format($accommodation->monthly_rent, 2) }}/month</span>
                    </div>
                    <div class="flex items-center text-gray-600 mb-3">
                        <i class="fas fa-users text-red-800 mr-2"></i>
                        <span>{{ $accommodation->type }} • Capacity: {{ $accommodation->capacity }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt text-red-800 mr-2"></i>
                        <span>Block {{ $accommodation->block }}, Floor {{ $accommodation->floor }}</span>
                    </div>
                    <a href="{{ route('accommodations.show', $accommodation) }}" class="inline-block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @auth
    @if(Auth::user()->isStudent())
        <!-- If user is logged in as student, go to student home -->
        <div class="text-center">
            <a href="{{ route('student.home') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                <i class="fas fa-list mr-2"></i>View Your Applications
            </a>
            <p class="text-sm text-gray-600 mt-2">Already applied? Check your application status here.</p>
        </div>
    @else
        <!-- If logged in but not student (landlord/welfare/admin) -->
        <div class="text-center">
            <a href="{{ route('accommodations.index') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                Browse All On-Campus Rooms
            </a>
        </div>
    @endif
@else
    <!-- If not logged in, go to public accommodations page -->
    <div class="text-center">
        <a href="{{ route('accommodations.index') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
            Browse All On-Campus Rooms
        </a>
    </div>
@endauth
    </div>
</div>




<!-- Off-Campus Section -->
<div id="off-campus" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">
            <i class="fas fa-search text-red-800 mr-3"></i>
            Off-Campus Properties
        </h2>
        <p class="text-lg text-gray-600 mb-8 max-w-3xl">
            Browse verified listings from trusted landlords near campus. Filter by price, location, and amenities.
        </p>

        @if($featuredProperties->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach($featuredProperties as $property)
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $property->title }}</h3>
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="fas fa-map-marker-alt text-red-800 mr-2"></i>
                        <span>{{ $property->city }}</span>
                    </div>
                    <div class="flex items-center text-gray-600 mb-2">
                        <i class="fas fa-tag text-red-800 mr-2"></i>
                        <span>P{{ number_format($property->monthly_rent, 2) }}/month</span>
                    </div>
                    <div class="flex items-center text-gray-600 mb-4">
                        <i class="fas fa-bed text-red-800 mr-2"></i>
                        <span>{{ $property->bedrooms }} bed • {{ $property->bathrooms }} bath</span>
                    </div>
                    <a href="{{ route('properties.show', $property) }}" class="inline-block w-full text-center bg-red-800 text-white px-4 py-2 rounded-lg hover:bg-red-900 transition">
                        View Details
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @auth
            @if(Auth::user()->isStudent())
                <div class="text-center">
                    <a href="{{ route('student.home') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                        <i class="fas fa-list mr-2"></i>View Your Applications
                    </a>
                    <p class="text-sm text-gray-600 mt-2">Already applied? Check your application status here.</p>
                </div>
            @else
                <div class="text-center">
                    <a href="{{ route('properties.index') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                        Browse All Off-Campus Properties
                    </a>
                </div>
            @endif
        @else
            <div class="text-center">
                <a href="{{ route('properties.index') }}" class="inline-block bg-red-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                    Browse All Off-Campus Properties
                </a>
            </div>
        @endauth
  </div>
</div>





<!-- How It Works -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">How It Works</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-800">1</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Browse</h3>
                <p class="text-gray-600">Explore on-campus rooms or off-campus properties</p>
            </div>
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-800">2</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Apply/Request</h3>
                <p class="text-gray-600">Submit applications or request viewings</p>
            </div>
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-800">3</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Get Approved</h3>
                <p class="text-gray-600">Welfare or landlords review your request</p>
            </div>
            <div class="text-center">
                <div class="bg-red-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl font-bold text-red-800">4</span>
                </div>
                <h3 class="font-semibold text-lg mb-2">Move In</h3>
                <p class="text-gray-600">Pay deposit and get your keys</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Find Your Home?</h2>
        <p class="text-xl mb-8 opacity-90">Join thousands of students who found their perfect accommodation through our platform.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-red-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-user-plus mr-2"></i>Get Started
            </a>
            <a href="{{ route('information.hub') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                <i class="fas fa-info-circle mr-2"></i>Visit Information Hub
            </a>
        </div>
    </div>
</div>
@endsection
