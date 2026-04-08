@extends('layouts.app')

@section('title', 'Application Details')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Application Details</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div>
            <a href="{{ route('student.applications') }}" class="text-red-800 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-2"></i>Back to applications
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $application->application_reference ?? 'Application #' . $application->id }}</h1>
                    <p class="text-gray-600 mt-2">Submitted {{ $application->created_at->format('d M Y H:i') }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                    {{ ucfirst($application->status) }}
                </span>
            </div>

            <div class="p-6 grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="xl:col-span-2 space-y-6">
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Application summary</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 text-sm">
                            <div>
                                <p class="text-gray-500">Student</p>
                                <p class="font-semibold text-gray-900">{{ $application->student->name }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Student ID</p>
                                <p class="font-semibold text-gray-900">{{ $application->student->student_id ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Assigned room</p>
                                <p class="font-semibold text-gray-900">{{ $application->accommodation->name ?? 'Pending welfare allocation' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Duration</p>
                                <p class="font-semibold text-gray-900">{{ $application->duration_months }} months</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Preferred move-in date</p>
                                <p class="font-semibold text-gray-900">{{ optional($application->preferred_move_in_date)->format('d M Y') ?? 'TBD' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Processed by</p>
                                <p class="font-semibold text-gray-900">{{ $application->processor->name ?? 'Awaiting review' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($application->special_requirements)
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-gray-900">Special requirements</h3>
                            <p class="text-gray-700 mt-4">{{ $application->special_requirements }}</p>
                        </div>
                    @endif

                    @if($application->rejection_reason)
                        <div class="bg-red-50 border border-red-100 rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-red-900">Rejection reason</h3>
                            <p class="text-red-800 mt-4">{{ $application->rejection_reason }}</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    @if($application->payment)
                        <div class="bg-white border border-gray-200 rounded-2xl p-6">
                            <h3 class="text-xl font-bold text-gray-900">Payment</h3>
                            <div class="mt-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Amount</span>
                                    <span class="font-semibold text-gray-900">{{ $application->payment->formatted_amount }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Status</span>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $application->payment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($application->payment->status) }}
                                    </span>
                                </div>
                            </div>

                            @if($application->payment->status === 'pending')
                                <form method="POST" action="{{ route('student.payments.process') }}" class="mt-5 space-y-3">
                                    @csrf
                                    <input type="hidden" name="payment_id" value="{{ $application->payment->id }}">
                                    <select name="payment_method" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                                        <option value="card">Card</option>
                                        <option value="bank_transfer">Bank transfer</option>
                                        <option value="mobile_money">Mobile money</option>
                                    </select>
                                    <button type="submit" class="w-full bg-red-800 text-white rounded-lg px-4 py-3 font-semibold hover:bg-red-900 transition">Pay now</button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-xl font-bold text-gray-900">Need assistance?</h3>
                        <p class="text-gray-600 mt-3">Use the virtual help desk for immigration, registration, or accommodation support.</p>
                        <a href="{{ route('student.support') }}" class="inline-flex items-center gap-2 mt-4 text-red-800 font-semibold hover:underline">
                            Open help desk
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
