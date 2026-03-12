@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-md w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-lock text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Forgot Password?</h2>
            <p class="text-gray-600 mt-2">No problem. Just let us know your email address and we will email you a password reset link.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-button w-full text-white font-semibold py-3 px-4 rounded-lg transition">
                Email Password Reset Link
            </button>

            <p class="text-center mt-6">
                <a href="{{ route('login') }}" class="auth-link hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Login
                </a>
            </p>
        </form>
    </div>
</div>
@endsection