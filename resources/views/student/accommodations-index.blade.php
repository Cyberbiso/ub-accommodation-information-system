@extends('layouts.app')

@section('title', 'Find Accommodation')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Find Accommodation
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-red-800 to-red-900 text-white rounded-lg shadow-lg mb-8 p-8 text-center">
            <h1 class="text-3xl font-bold mb-4">Find Your Perfect Student Home</h1>
            <p class="text-xl opacity-90">Choose between on-campus living or off-campus properties</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- On-Campus Card -->
            <a href="{{ route('student.on-campus') }}" 
               class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="h-48 bg-gradient-to-r from-red-800 to-red-900 flex items-center justify-center">
                    <i class="fas fa-building text-white text-6xl"></i>
                </div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold mb-2">On-Campus Housing</h2>
                    <p class="text-gray-600 mb-4">Live at the heart of university life. Apply for university-managed accommodation directly through our platform.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-red-800">{{ $stats['on_campus_count'] }}</span>
                        <span class="text-gray-600">Available Rooms</span>
                    </div>
                    <div class="mt-6 bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Includes:</h3>
                        <ul class="space-y-1 text-sm">
                            <li><i class="fas fa-wifi text-red-800 mr-2"></i> WiFi included</li>
                            <li><i class="fas fa-shield-alt text-red-800 mr-2"></i> 24/7 Security</li>
                            <li><i class="fas fa-utensils text-red-800 mr-2"></i> Meal plans available</li>
                            <li><i class="fas fa-bus text-red-800 mr-2"></i> Close to classes</li>
                        </ul>
                    </div>
                    <div class="mt-6 text-center">
                        <span class="inline-block bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                            Browse On-Campus
                        </span>
                    </div>
                </div>
            </a>

            <!-- Off-Campus Card -->
            <a href="{{ route('student.off-campus') }}" 
               class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition transform hover:-translate-y-1">
                <div class="h-48 bg-gradient-to-r from-red-900 to-red-800 flex items-center justify-center">
                    <i class="fas fa-search text-white text-6xl"></i>
                </div>
                <div class="p-8">
                    <h2 class="text-2xl font-bold mb-2">Off-Campus Properties</h2>
                    <p class="text-gray-600 mb-4">Find your ideal private accommodation near campus. Browse verified listings from trusted landlords.</p>
                    <div class="flex justify-between items-center">
                        <span class="text-3xl font-bold text-red-800">{{ $stats['off_campus_count'] }}</span>
                        <span class="text-gray-600">Available Properties</span>
                    </div>
                    <div class="mt-6 bg-red-50 p-4 rounded-lg">
                        <h3 class="font-semibold mb-2">Features:</h3>
                        <ul class="space-y-1 text-sm">
                            <li><i class="fas fa-check-circle text-red-800 mr-2"></i> Verified landlords</li>
                            <li><i class="fas fa-calendar-check text-red-800 mr-2"></i> Schedule viewings</li>
                            <li><i class="fas fa-credit-card text-red-800 mr-2"></i> Secure payments</li>
                            <li><i class="fas fa-filter text-red-800 mr-2"></i> Advanced filters</li>
                        </ul>
                    </div>
                    <div class="mt-6 text-center">
                        <span class="inline-block bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">
                            Browse Off-Campus
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Help Section -->
        <div class="mt-12 bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-4">Need Help Deciding?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-lg mb-2">On-Campus Pros</h3>
                    <ul class="space-y-2">
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> Close to classes and facilities</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> All utilities included</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> Easy to make friends</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> 24/7 security and support</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-lg mb-2">Off-Campus Pros</h3>
                    <ul class="space-y-2">
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> More independence</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> Wider variety of options</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> Often more space</li>
                        <li><i class="fas fa-plus-circle text-green-600 mr-2"></i> Choose your roommates</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection