@extends('layouts.public')

@section('title', 'UB-UniStay')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome to UB-UniStay</h1>
        <p class="text-xl mb-8 max-w-3xl">
            Your complete pre-arrival and accommodation solution for the University of Botswana
        </p>
        
        <!-- Hub Switcher Buttons -->
        <div class="flex flex-wrap gap-4 justify-center md:justify-start">
            <a href="#accommodation-hub" class="bg-white text-red-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-home mr-2"></i>Accommodation Hub
            </a>
            <a href="#information-hub" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                <i class="fas fa-info-circle mr-2"></i>Information Hub
            </a>
        </div>
    </div>
</div>

<!-- Quick Stats (System-wide) -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ number_format($stats['on_campus_rooms']) }}</div>
                <div class="text-gray-600">On-Campus Rooms</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ number_format($stats['off_campus_properties']) }}</div>
                <div class="text-gray-600">Off-Campus Properties</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ number_format($stats['active_landlords']) }}</div>
                <div class="text-gray-600">Verified Landlords</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold text-red-800 mb-2">{{ number_format($stats['happy_students']) }}</div>
                <div class="text-gray-600">Registered Students</div>
            </div>
        </div>
    </div>
</div>

<!-- ACCOMMODATION HUB SECTION -->
<div id="accommodation-hub" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-home text-red-800 mr-3"></i>
                Accommodation Hub
            </h2>
            <a href="{{ url('/accommodation-hub') }}" class="text-red-800 hover:text-red-900 font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <p class="text-lg text-gray-600 mb-8 max-w-3xl">
            Find and apply for on-campus housing or browse off-campus properties from trusted landlords.
        </p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- On-Campus Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-building"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">On-Campus Housing</h3>
                    <p class="text-gray-600 mb-4">Apply for university-managed accommodation directly through our platform.</p>
                    <a href="{{ auth()->check() && auth()->user()->isStudent() ? route('student.accommodations') : route('accommodations.index') }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Browse Rooms <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Off-Campus Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Off-Campus Properties</h3>
                    <p class="text-gray-600 mb-4">Browse verified listings from trusted landlords near campus.</p>
                    <a href="{{ url('/properties') }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Find Properties <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Viewing Requests Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Viewing Requests</h3>
                    <p class="text-gray-600 mb-4">Request property viewings and get instant responses from landlords.</p>
                    <a href="{{ auth()->guest()
                        ? route('login')
                        : (auth()->user()->isStudent()
                            ? route('student.viewing-requests')
                            : (auth()->user()->isLandlord()
                                ? route('landlord.viewing-requests')
                                : route('dashboard'))) }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Manage Viewings <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Accommodation Stats -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-red-800">{{ number_format($stats['on_campus_rooms']) }}</div>
                    <div class="text-sm text-gray-600">Available Rooms</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-800">{{ number_format($stats['off_campus_properties']) }}</div>
                    <div class="text-sm text-gray-600">Properties</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-800">{{ number_format($stats['active_landlords']) }}</div>
                    <div class="text-sm text-gray-600">Landlords</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-800">{{ number_format($stats['happy_students']) }}</div>
                    <div class="text-sm text-gray-600">Registered Students</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- INFORMATION HUB SECTION -->
<div id="information-hub" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-info-circle text-red-800 mr-3"></i>
                Information Hub
            </h2>
            <a href="{{ url('/information-hub') }}" class="text-red-800 hover:text-red-900 font-medium">
                View All <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        
        <p class="text-lg text-gray-600 mb-8 max-w-3xl">
            Everything you need to know before and after arrival – campus offices, immigration guides, and downloadable resources.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Campus Directory Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Campus Directory</h3>
                    <p class="text-gray-600 mb-4">Find offices, departments, and key locations on campus.</p>
                    <a href="{{ route('information.campus-directory') }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Explore <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Immigration Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-passport"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Immigration</h3>
                    <p class="text-gray-600 mb-4">Visa requirements, study permits, and important deadlines for international students.</p>
                    <a href="{{ route('information.immigration') }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Learn More <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Resources Card -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200 hover:shadow-xl transition">
                <div class="h-2 bg-red-800"></div>
                <div class="p-6">
                    <div class="text-red-800 text-3xl mb-4">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Resources</h3>
                    <p class="text-gray-600 mb-4">Downloadable guides, forms, and helpful links for new and returning students.</p>
                    <a href="{{ route('information.resources') }}" class="text-red-800 hover:text-red-900 font-medium inline-flex items-center">
                        Browse Resources <i class="fas fa-arrow-right ml-2 text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Information Stats -->
        <div class="mt-8 bg-white rounded-lg p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                <div>
                    <div class="text-2xl font-bold text-red-800">10+</div>
                    <div class="text-sm text-gray-600">Campus Offices</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-800">8+</div>
                    <div class="text-sm text-gray-600">Immigration Guides</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-red-800">12+</div>
                    <div class="text-sm text-gray-600">Resources</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action - Unified -->
<div class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Your Journey?</h2>
        <p class="text-xl mb-8 opacity-90">Join thousands of students who use UB-UniStay for a smooth transition.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-red-800 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </a>
            <a href="#accommodation-hub" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                <i class="fas fa-home mr-2"></i>Find Housing
            </a>
            <a href="#information-hub" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-red-800 transition">
                <i class="fas fa-info-circle mr-2"></i>Get Information
            </a>
        </div>
    </div>
</div>
@endsection
