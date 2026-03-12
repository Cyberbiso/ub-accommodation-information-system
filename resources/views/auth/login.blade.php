@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-md w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-home text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
            <p class="text-gray-600 mt-2">Sign in to your account</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 rounded-lg p-3">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm auth-link hover:underline">
                        Forgot password?
                    </a>
                @endif
            </div>

            <button type="submit" class="auth-button w-full text-white font-semibold py-3 px-4 rounded-lg transition">
                Sign In
            </button>

            <p class="text-center mt-6 text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="auth-link font-semibold hover:underline">
                    Register here
                </a>
            </p>
        </form>
    </div>
</div>
@endsection