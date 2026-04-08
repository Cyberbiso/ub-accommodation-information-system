@extends('layouts.app')

@section('title', 'Edit Property')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Edit Property</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $property->title }}</h1>
                    <p class="text-gray-600 mt-2">Update pricing, location, and amenity details for students.</p>
                </div>
                <form method="POST" action="{{ route('landlord.properties.destroy', $property) }}" onsubmit="return confirm('Remove this property listing?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="border border-red-200 text-red-700 px-4 py-3 rounded-lg font-semibold hover:bg-red-50 transition">Delete listing</button>
                </form>
            </div>

            <form method="POST" action="{{ route('landlord.properties.update', $property) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                @include('landlord._property-form', ['property' => $property])

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('landlord.properties') }}" class="border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
