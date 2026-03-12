<!-- resources/views/landing.blade.php -->
@extends('layouts.public')

@section('title', 'Home - Find Your Perfect Student Home')

@section('content')
<!-- Hero Section -->
<div class="hero-gradient text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Find Your Perfect Student Home
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                Your one-stop platform for on-campus and off-campus accommodation at the University of Botswana
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('public.accommodations') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-building mr-2"></i>Browse On-Campus
                </a>
                <a href="{{ route('public.properties') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                    <i class="fas fa-search mr-2"></i>Find Off-Campus
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $stats['on_campus_rooms'] }}+</div>
                <div class="text-gray-600">On-Campus Rooms</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $stats['off_campus_properties'] }}+</div>
                <div class="text-gray-600">Off-Campus Properties</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $stats['active_landlords'] }}+</div>
                <div class="text-gray-600">Trusted Landlords</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $stats['happy_students'] }}+</div>
                <div class="text-gray-600">Happy Students</div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center mb-12">Why Choose Our Platform?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-building"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">On-Campus Housing</h3>
                <p class="text-gray-600">Apply for university-managed accommodation directly through our platform.</p>
            </div>
            
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Off-Campus Properties</h3>
                <p class="text-gray-600">Browse verified listings from trusted landlords near campus.</p>
            </div>
            
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Easy Viewing Requests</h3>
                <p class="text-gray-600">Request property viewings and get instant responses from landlords.</p>
            </div>
            
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-credit-card"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Secure Payments</h3>
                <p class="text-gray-600">Pay application fees, deposits, and rent securely online.</p>
            </div>
            
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Verified Listings</h3>
                <p class="text-gray-600">All properties are verified by our team to ensure safety and quality.</p>
            </div>
            
            <div class="feature-card bg-white p-6 rounded-lg shadow-md text-center">
                <div class="text-indigo-600 text-4xl mb-4">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Real-Time Updates</h3>
                <p class="text-gray-600">Get instant notifications on application status and viewing requests.</p>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action -->
<div class="hero-gradient text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Find Your Home?</h2>
        <p class="text-xl mb-8 opacity-90">Join thousands of students who have found their perfect accommodation through our platform.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('register') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                <i class="fas fa-user-plus mr-2"></i>Get Started Now
            </a>
            <a href="{{ route('contact') }}" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition">
                <i class="fas fa-envelope mr-2"></i>Contact Us
            </a>
        </div>
    </div>
</div>
@endsection