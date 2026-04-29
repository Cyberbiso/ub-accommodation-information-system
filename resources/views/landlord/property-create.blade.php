@extends('layouts.app')

@section('title', 'Advertise Property')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Advertise Property</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Create a verified listing</h1>
                <p class="text-gray-600 mt-2">Add enough location detail for students to compare routes, travel distance, and nearby amenities.</p>
            </div>

            <form method="POST" action="{{ route('landlord.properties.store') }}" enctype="multipart/form-data" class="space-y-6" onsubmit="if(this.dataset.submitted){return false;} this.dataset.submitted='1'; this.querySelector('button[type=submit]').disabled=true; this.querySelector('button[type=submit]').innerText='Submitting…';">
                @csrf
                @include('landlord._property-form')

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('landlord.properties') }}" class="border border-gray-300 text-gray-700 px-4 py-3 rounded-lg font-semibold hover:bg-gray-50 transition">Cancel</a>
                    <button type="submit" class="bg-red-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-red-900 transition disabled:opacity-60">Submit for approval</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
