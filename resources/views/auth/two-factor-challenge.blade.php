@extends('layouts.auth')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-md w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-mobile-alt text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Two-Factor Authentication</h2>
            <p class="text-gray-600 mt-2">Please enter the authentication code from your authenticator app.</p>
        </div>

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            <div class="mb-6">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Authentication Code</label>
                <input id="code" type="text" name="code" inputmode="numeric" autofocus
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
                @error('code')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <p class="text-center text-gray-600 mb-4">- OR -</p>

            <div class="mb-6">
                <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">Recovery Code</label>
                <input id="recovery_code" type="text" name="recovery_code"
                    class="auth-input w-full px-4 py-3 rounded-lg border-gray-300 focus:border-red-800 focus:ring-red-800">
            </div>

            <button type="submit" class="auth-button w-full text-white font-semibold py-3 px-4 rounded-lg transition">
                Continue
            </button>
        </form>
    </div>
</div>
@endsection