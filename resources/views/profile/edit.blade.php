@extends('layouts.auth')

@section('title', 'Profile')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Information</h2>
                
                @if (session('status') === 'profile-updated')
                    <div class="mb-4 text-sm text-green-600 bg-green-100 border border-green-400 rounded-lg p-3">
                        Profile updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}"
                            class="auth-input mt-1 block w-full rounded-md border-gray-300 focus:border-red-800 focus:ring-red-800">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}"
                            class="auth-input mt-1 block w-full rounded-md border-gray-300 focus:border-red-800 focus:ring-red-800">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="auth-button text-white px-6 py-2 rounded-md">
                            Save
                        </button>
                    </div>
                </form>

                <hr class="my-8">

                <h2 class="text-2xl font-bold text-gray-900 mb-6">Update Password</h2>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="auth-input mt-1 block w-full rounded-md border-gray-300 focus:border-red-800 focus:ring-red-800">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" id="password"
                            class="auth-input mt-1 block w-full rounded-md border-gray-300 focus:border-red-800 focus:ring-red-800">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="auth-input mt-1 block w-full rounded-md border-gray-300 focus:border-red-800 focus:ring-red-800">
                    </div>

                    <div>
                        <button type="submit" class="auth-button text-white px-6 py-2 rounded-md">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection