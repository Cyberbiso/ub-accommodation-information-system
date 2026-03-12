@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-md w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-shield-alt text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Confirm Password</h2>
            <p class="text-gray-600 mt-2">This is a secure area of the application. Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" required autofocus
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="auth-button w-full text-white font-semibold py-3 px-4 rounded-lg transition">
                Confirm
            </button>
        </form>
    </div>
</div>
@endsection