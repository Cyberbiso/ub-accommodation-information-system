@extends('layouts.app')

@section('title', 'Edit Room')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">Edit Room</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

        <div class="mb-4">
            <a href="{{ route('welfare.accommodations') }}" class="text-red-800 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Accommodations
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="bg-red-800 px-6 py-4">
                <h3 class="text-lg font-semibold text-white">Edit: {{ $accommodation->name }}</h3>
            </div>

            <form action="{{ route('welfare.accommodations.update', $accommodation) }}" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Room Name <span class="text-red-600">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name', $accommodation->name) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800 @error('name') border-red-500 @enderror">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            Type <span class="text-red-600">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800">
                            @foreach(['single','shared','family'] as $t)
                                <option value="{{ $t }}" {{ old('type', $accommodation->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-1">
                            Capacity <span class="text-red-600">*</span>
                        </label>
                        <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $accommodation->capacity) }}" min="1" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800">
                    </div>

                    <div>
                        <label for="monthly_rent" class="block text-sm font-medium text-gray-700 mb-1">
                            Monthly Rent (BWP) <span class="text-red-600">*</span>
                        </label>
                        <input id="monthly_rent" type="number" name="monthly_rent" value="{{ old('monthly_rent', $accommodation->monthly_rent) }}" min="0" step="0.01" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800">
                    </div>

                    <div>
                        <label for="block" class="block text-sm font-medium text-gray-700 mb-1">Block</label>
                        <input id="block" type="text" name="block" value="{{ old('block', $accommodation->block) }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800">
                    </div>

                    <div>
                        <label for="floor" class="block text-sm font-medium text-gray-700 mb-1">Floor</label>
                        <input id="floor" type="number" name="floor" value="{{ old('floor', $accommodation->floor) }}" min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:border-red-800">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Availability</label>
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" name="is_available" value="1"
                                   {{ old('is_available', $accommodation->is_available) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                            <span class="ml-2 text-sm text-gray-700">Room is available</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('welfare.accommodations') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900 transition text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
