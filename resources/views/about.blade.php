@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<div class="container py-5">
    <h1 class="display-4 mb-4">About UniStay</h1>
    
    <div class="row">
        <div class="col-lg-8">
            <p class="lead mb-4">
                UniStay is a comprehensive platform designed to help students at the University of Botswana find suitable accommodation, whether on-campus or off-campus.
            </p>
            
            <h2 class="h3 mb-3">Our Mission</h2>
            <p class="mb-4">
                To provide a seamless, transparent, and efficient platform that connects students with quality accommodation options while simplifying the application and payment process.
            </p>
            
            <h2 class="h3 mb-3">What We Offer</h2>
            <ul class="list-unstyled mb-4">
                <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>On-Campus Housing:</strong> Apply directly for university-managed accommodation through our platform.</li>
                <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Off-Campus Properties:</strong> Browse verified listings from trusted landlords near campus.</li>
                <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Viewing Requests:</strong> Easily schedule viewings with landlords.</li>
                <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Secure Payments:</strong> Pay application fees, deposits, and rent online securely.</li>
                <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Real-Time Updates:</strong> Get instant notifications on your application and viewing status.</li>
            </ul>
            
            <h2 class="h3 mb-3">Our Team</h2>
            <p class="mb-4">
                The system was developed as a final year project by a team of dedicated students from the University of Botswana, in collaboration with the Department of Computer Science and the Student Welfare Office.
            </p>
        </div>
        
        <div class="col-lg-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Contact Information</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i> Gaborone, Botswana</li>
                        <li class="mb-2"><i class="fas fa-phone text-primary me-2"></i> +267 123 4567</li>
                        <li class="mb-2"><i class="fas fa-envelope text-primary me-2"></i> info@ub.ac.bw</li>
                    </ul>
                    <hr>
                    <p class="mb-0">For any inquiries or support, please visit our <a href="{{ url('/contact') }}">Contact Page</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection