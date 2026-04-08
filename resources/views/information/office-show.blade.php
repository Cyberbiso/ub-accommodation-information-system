@extends('layouts.public')

@section('title', $office->office_name)

@section('content')
<div class="hero-gradient text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-3xl font-bold mb-2">{{ $office->office_name }}</h1>
        <p class="text-lg opacity-90">{{ ucfirst(str_replace('_', ' ', $office->category)) }}</p>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-2xl shadow p-8">
        <p class="text-gray-700 text-lg">{{ $office->description }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-500">Location</p>
                <p class="text-lg font-semibold text-gray-900 mt-2">{{ $office->full_location }}</p>
            </div>
            @if($office->hours)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Operating Hours</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $office->hours }}</p>
                </div>
            @endif
            @if($office->phone)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ $office->phone }}</p>
                </div>
            @endif
            @if($office->email)
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-sm text-gray-500">Email</p>
                    <a href="mailto:{{ $office->email }}" class="text-lg font-semibold text-red-800 mt-2 inline-block">{{ $office->email }}</a>
                </div>
            @endif
        </div>

        <div class="mt-8">
            <a href="{{ route('information.campus-directory') }}" class="inline-flex items-center text-red-800 font-semibold hover:underline">
                <i class="fas fa-arrow-left mr-2"></i>Back to Campus Directory
            </a>
        </div>
    </div>
</div>
@endsection
