<!-- resources/views/about.blade.php -->
@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">About UB-UniStay</h1>
    
    <div class="prose max-w-none">
        <p class="text-lg mb-6">
            UB-UniStay is a comprehensive platform designed to help students at the University of Botswana find suitable accommodation, whether on-campus or off-campus.
        </p>
        
        <h2 class="text-2xl font-semibold mb-4">Our Mission</h2>
        <p class="mb-6">
            To provide a seamless, transparent, and efficient platform that connects students with quality accommodation options while simplifying the application and payment process.
        </p>
        
        <h2 class="text-2xl font-semibold mb-4">What We Offer</h2>
        <ul class="list-disc pl-6 mb-6 space-y-2">
            <li><strong>On-Campus Housing:</strong> Apply directly for university-managed accommodation through our platform.</li>
            <li><strong>Off-Campus Properties:</strong> Browse verified listings from trusted landlords near campus.</li>
            <li><strong>Viewing Requests:</strong> Easily schedule viewings with landlords.</li>
            <li><strong>Secure Payments:</strong> Pay application fees, deposits, and rent online securely.</li>
            <li><strong>Real-Time Updates:</strong> Get instant notifications on your application and viewing status.</li>
        </ul>
        
        <h2 class="text-2xl font-semibold mb-4">Our Team</h2>
        <p class="mb-6">
            The system was developed as a final year project by a team of dedicated students from the University of Botswana, in collaboration with the Department of Computer Science and the Student Welfare Office.
        </p>
        
        <h2 class="text-2xl font-semibold mb-4">Contact Us</h2>
        <p>
            For any inquiries or support, please visit our <a href="{{ route('contact') }}" class="text-indigo-600 hover:underline">Contact Page</a> or email us at info@ub.ac.bw.
        </p>
    </div>
</div>
@endsection