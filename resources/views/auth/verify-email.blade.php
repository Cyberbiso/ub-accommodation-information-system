@extends('layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-md w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-envelope text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Verify Your Email</h2>
            <p class="text-gray-600 mt-2">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 rounded-lg p-3">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="mt-6 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="auth-button text-white px-6 py-2 rounded-md">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-600 hover:text-red-800">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection