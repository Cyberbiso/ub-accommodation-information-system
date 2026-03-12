@extends('layouts.app')

@section('title', 'Request Viewing')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Request Property Viewing
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Property Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        @if($property->photos && count($property->photos) > 0)
                            <img src="{{ asset('storage/' . $property->photos[0]) }}" 
                                 alt="{{ $property->title }}" 
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-300 rounded-lg flex items-center justify-center">
                                <i class="fas fa-home text-gray-500 text-2xl"></i>
                            </div>
                        @endif
                        <div class="ml-4">
                            <h3 class="font-bold text-lg">{{ $property->title }}</h3>
                            <p class="text-gray-600">{{ $property->city }}</p>
                            <p class="text-red-800 font-bold">P{{ number_format($property->monthly_rent, 2) }}/month</p>
                        </div>
                    </div>
                </div>

                <!-- Check for existing pending request -->
                @php
                    $existingRequest = App\Models\ViewingRequest::where('student_id', Auth::id())
                        ->where('property_id', $property->id)
                        ->where('status', 'pending')
                        ->first();
                @endphp

                @if($existingRequest)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle text-yellow-400 mr-3"></i>
                            <div>
                                <p class="text-sm text-yellow-700">
                                    You already have a pending viewing request for this property.
                                </p>
                                <a href="{{ route('student.viewing-requests.cancel', $existingRequest) }}" 
                                   class="text-sm text-red-600 hover:underline mt-2 inline-block"
                                   onclick="return confirm('Are you sure you want to cancel your existing request?')">
                                    Cancel existing request
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.viewing-requests.store', $property) }}">
                    @csrf

                    <!-- Preferred Date -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Date <span class="text-red-600">*</span>
                        </label>
                        <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" 
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                        @error('preferred_date')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preferred Time -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Time <span class="text-red-600">*</span>
                        </label>
                        <select name="preferred_time" required class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800">
                            <option value="">Select time</option>
                            <option value="09:00" {{ old('preferred_time') == '09:00' ? 'selected' : '' }}>09:00 AM</option>
                            <option value="10:00" {{ old('preferred_time') == '10:00' ? 'selected' : '' }}>10:00 AM</option>
                            <option value="11:00" {{ old('preferred_time') == '11:00' ? 'selected' : '' }}>11:00 AM</option>
                            <option value="12:00" {{ old('preferred_time') == '12:00' ? 'selected' : '' }}>12:00 PM</option>
                            <option value="13:00" {{ old('preferred_time') == '13:00' ? 'selected' : '' }}>01:00 PM</option>
                            <option value="14:00" {{ old('preferred_time') == '14:00' ? 'selected' : '' }}>02:00 PM</option>
                            <option value="15:00" {{ old('preferred_time') == '15:00' ? 'selected' : '' }}>03:00 PM</option>
                            <option value="16:00" {{ old('preferred_time') == '16:00' ? 'selected' : '' }}>04:00 PM</option>
                        </select>
                        @error('preferred_time')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Phone -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Phone <span class="text-red-600">*</span>
                        </label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone', Auth::user()->phone) }}" required
                               class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800"
                               placeholder="e.g., 71 234 567">
                        @error('contact_phone')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Message to Landlord -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Message to Landlord (Optional)
                        </label>
                        <textarea name="message" rows="4" 
                                  class="w-full px-4 py-2 border rounded-lg focus:border-red-800 focus:ring-red-800"
                                  placeholder="Any specific questions or information you'd like to share?">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            The landlord will be notified of your request and will respond with a confirmed date and time.
                        </p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('student.properties.show', $property) }}" 
                           class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </a>
                        <button type="submit" class="bg-red-800 text-white px-6 py-2 rounded-lg hover:bg-red-900 transition"
                                {{ $existingRequest ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection