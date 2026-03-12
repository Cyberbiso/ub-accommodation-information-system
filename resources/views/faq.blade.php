<!-- resources/views/faq.blade.php -->
@extends('layouts.public')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-4xl font-bold mb-8">Frequently Asked Questions</h1>
    
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">How do I apply for on-campus accommodation?</h3>
            <p class="text-gray-600">Register as a student, browse available accommodations, and click "Apply Now" on your preferred room. You'll need to pay a non-refundable application fee of P50.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">How do I list my property as a landlord?</h3>
            <p class="text-gray-600">Register as a landlord, go to your dashboard, and click "Add New Property". Fill in all the details, upload photos, and submit for admin approval.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">How long does application approval take?</h3>
            <p class="text-gray-600">On-campus applications are typically processed within 3-5 working days by the welfare office. You'll receive an email notification once your status changes.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">Can I request to view a property before applying?</h3>
            <p class="text-gray-600">Yes! On any property listing, click "Request Viewing", select your preferred date, and send a message to the landlord. They will respond with a confirmed time.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">What payment methods are accepted?</h3>
            <p class="text-gray-600">We accept credit/debit cards and bank transfers. All payments are processed securely through our payment gateway.</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold mb-2">Is my deposit refundable?</h3>
            <p class="text-gray-600">Deposits are typically refundable at the end of your lease, subject to the terms of your tenancy agreement and any deductions for damages.</p>
        </div>
    </div>
</div>
@endsection
