<!-- resources/views/contact.blade.php -->
@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Contact Us</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
        <!-- Contact Form -->
        <div>
            <h2 class="text-2xl font-semibold mb-6">Send Us a Message</h2>
            
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition">
                    Send Message
                </button>
            </form>
        </div>
        
        <!-- Contact Information -->
        <div>
            <h2 class="text-2xl font-semibold mb-6">Get in Touch</h2>
            
            <div class="bg-gray-50 p-6 rounded-lg space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="text-indigo-600 text-xl">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Address</h3>
                        <p class="text-gray-600">University of Botswana<br>
                           Private Bag UB0022<br>
                           Gaborone, Botswana</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="text-indigo-600 text-xl">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Phone</h3>
                        <p class="text-gray-600">+267 123 4567</p>
                        <p class="text-gray-600">+267 123 4568</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="text-indigo-600 text-xl">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Email</h3>
                        <p class="text-gray-600">info@ub.ac.bw</p>
                        <p class="text-gray-600">support@ub.ac.bw</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="text-indigo-600 text-xl">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Office Hours</h3>
                        <p class="text-gray-600">Monday - Friday: 8:00 AM - 5:00 PM</p>
                        <p class="text-gray-600">Saturday: 9:00 AM - 1:00 PM</p>
                        <p class="text-gray-600">Sunday: Closed</p>
                    </div>
                </div>
            </div>
            
            <!-- Map (placeholder) -->
            <div class="mt-6 bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                <p class="text-gray-500">Map will be displayed here</p>
            </div>
        </div>
    </div>
</div>
@endsection