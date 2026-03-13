@extends('layouts.app')

@section('title', 'Accommodation Application Form')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Accommodation Application Form
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <!-- Header -->
            <div class="bg-red-800 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Department of Student Welfare</h1>
                <h2 class="text-xl mt-2">Application for On-Campus Accommodation</h2>
            </div>
            
            <!-- Instructions -->
            <div class="p-6 bg-yellow-50 border-l-4 border-yellow-400">
                <p class="font-bold text-red-800 mb-2">PLEASE READ CAREFULLY</p>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                    <li>All students interested in securing on-campus accommodation must complete this application.</li>
                    <li class="font-bold text-red-600">LATE APPLICATIONS WILL BE REJECTED.</li>
                    <li class="font-bold">AN APPLICATION IS NOT A GUARANTEE FOR A PLACE IN RESIDENCES.</li>
                    <li class="font-bold">ALL INFORMATION NEEDS TO BE FILLED OUT COMPLETELY.</li>
                    <li class="font-bold">COMPLETE IN CAPITAL LETTERS:</li>
                </ul>
            </div>

            <!-- FIXED: Changed from .apply.submit to .apply -->
       <form method="POST" action="{{ route('student.applications.store') }}" class="p-6 space-y-8">            @csrf

               <!-- 1) Personal Details -->
<div class="border-2 border-gray-300 p-6 rounded-lg">
    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">1) Personal Details</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Student ID:</label>
            <input type="text" name="student_id" value="{{ old('student_id', Auth::user()->student_id) }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase bg-gray-100"
                   placeholder="e.g., 201905436" required readonly>
            <p class="text-xs text-gray-500 mt-1">Auto-filled from registration</p>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Surname:</label>
            <input type="text" name="surname" value="{{ old('surname', Auth::user()->surname ?? Auth::user()->name) }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase bg-gray-100"
                   required readonly>
            <p class="text-xs text-gray-500 mt-1">Auto-filled from registration</p>
        </div>
        
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">First Name:</label>
            <input type="text" name="first_name" value="{{ old('first_name', explode(' ', Auth::user()->name ?? '')[0]) }}" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase bg-gray-100"
                   required readonly>
            <p class="text-xs text-gray-500 mt-1">Auto-filled from registration</p>
        </div> 
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Gender:</label>
            <select name="gender" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
    </div>
   



                    <!-- Contact Numbers -->
                    <h3 class="text-xl font-bold mt-8 mb-4">2) Contact Number:</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="telephone" value="{{ old('telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="mobile" value="{{ old('mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile (alternate):</label>
                            <input type="tel" name="mobile_alternate" value="{{ old('mobile_alternate') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">University Email:</label>
                            <input type="email" name="university_email" value="{{ old('university_email', Auth::user()->email) }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                            <p class="text-xs text-gray-600 mt-1">Official means of communication</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Other Email:</label>
                            <input type="email" name="other_email" value="{{ old('other_email') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                </div>

                <!-- 3) Correspondence Address -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">3) Correspondence Address</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Street/Box:</label>
                            <input type="text" name="correspondence_street" value="{{ old('correspondence_street') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">City/Town:</label>
                            <input type="text" name="correspondence_city" value="{{ old('correspondence_city') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Country:</label>
                            <input type="text" name="correspondence_country" value="{{ old('correspondence_country', 'Botswana') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Postal Code:</label>
                            <input type="text" name="correspondence_postal" value="{{ old('correspondence_postal') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                    </div>
                </div>

                <!-- 4) Permanent Address -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">4) Permanent Address</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Street/Box:</label>
                            <input type="text" name="permanent_street" value="{{ old('permanent_street') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">City/Town:</label>
                            <input type="text" name="permanent_city" value="{{ old('permanent_city') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Country:</label>
                            <input type="text" name="permanent_country" value="{{ old('permanent_country', 'Botswana') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Postal Code:</label>
                            <input type="text" name="permanent_postal" value="{{ old('permanent_postal') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                    </div>
                </div>

                <!-- 5) Additional Details -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">5) Additional Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Marital Status:</label>
                            <select name="marital_status" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                                <option value="">Select Status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Date of Birth:</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Place of Birth:</label>
                            <input type="text" name="place_of_birth" value="{{ old('place_of_birth') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nationality:</label>
                            <input type="text" name="nationality" value="{{ old('nationality', 'Botswana') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                    </div>
                </div>

                <!-- 6) Preferred Move-In Date -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">6) Preferred Move-In Date</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Preferred Move-In Date: <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="preferred_move_in_date"
                                   value="{{ old('preferred_move_in_date') }}"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Must be a future date.</p>
                            @error('preferred_move_in_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                Duration (months): <span class="text-red-600">*</span>
                            </label>
                            <select name="duration_months" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                                <option value="">Select duration</option>
                                @foreach([6, 9, 12, 18, 24] as $months)
                                    <option value="{{ $months }}" {{ old('duration_months') == $months ? 'selected' : '' }}>
                                        {{ $months }} months
                                    </option>
                                @endforeach
                            </select>
                            @error('duration_months')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 7) Emergency Contact -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">6) Emergency Contact/Next of Kin</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name:</label>
                            <input type="text" name="emergency_name" value="{{ old('emergency_name') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Relationship:</label>
                            <input type="text" name="emergency_relationship" value="{{ old('emergency_relationship') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="emergency_telephone" value="{{ old('emergency_telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="emergency_mobile" value="{{ old('emergency_mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Address:</label>
                            <input type="text" name="emergency_address" value="{{ old('emergency_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase" required>
                        </div>
                    </div>
                </div>

                <!-- 13) Reasons for Applying -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">13) Reasons for Applying</h3>
                    
                    <p class="text-sm text-gray-700 mb-4">State reasons why you are applying for accommodation on campus:</p>
                    
                    <textarea name="reasons" rows="4" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>{{ old('reasons') }}</textarea>
                    
                    <!-- Disability Checkbox -->
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <label class="flex items-center">
                            <input type="checkbox" name="has_disability" value="1" class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                            <span class="ml-2 text-gray-700">I have a disability (Medical certificate will be required)</span>
                        </label>
                    </div>
                </div>

                <!-- Medical Certificate Upload (shown only if disability checked via JavaScript) -->
                <div id="medical_certificate_section" class="border-2 border-gray-300 p-6 rounded-lg hidden">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">Medical Certificate</h3>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Upload Medical Certificate:</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-red-800 transition">
                            <input type="file" name="medical_certificate" id="medical_certificate" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            <button type="button" onclick="document.getElementById('medical_certificate').click()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                                <i class="fas fa-upload mr-2"></i>Upload Medical Certificate
                            </button>
                            <p class="text-xs text-gray-500 mt-2">PDF, JPG or PNG (Max 2MB)</p>
                            <div id="medical_preview" class="mt-2 text-left hidden">
                                <i class="fas fa-check-circle text-green-600 mr-1"></i>
                                <span class="text-sm text-gray-600" id="medical_name"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 15) Regulations/Clearance -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">15) Regulations/Clearance</h3>
                    
                    <p class="text-sm text-gray-700 mb-4">Have you ever received a warning from Student Welfare regarding Residence Regulations/Clearance? (To be verified by the office):</p>
                    
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" name="previous_warning" value="yes" class="text-red-800 focus:ring-red-800">
                            <span class="ml-2">Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="previous_warning" value="no" class="text-red-800 focus:ring-red-800" checked>
                            <span class="ml-2">No</span>
                        </label>
                    </div>
                </div>

                <!-- Important Notes -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6">
                    <h3 class="font-bold text-red-800 mb-2">Carefully note the following:</h3>
                    <ul class="list-disc list-inside space-y-2 text-sm text-gray-700">
                        <li>Be informed that the University of Botswana Halls of Residence do not have adequate bed spaces for all students. It is advisable to seek alternative accommodation in case your applications are not successful.</li>
                        <li>Cooking is not allowed in the undergraduate hostels as refectories are available on campus.</li>
                        <li>Accommodation is offered on an annual basis. Once accommodated a student may opt for off-campus at the end of the academic year. Requests to move off-campus in the middle of the academic year are not allowed!</li>
                    </ul>
                </div>

                <!-- Declaration and Signature -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">Declaration and Signature</h3>
                    
                    <p class="text-sm text-gray-700 mt-4">I confirm that the information I have provided in this application for accommodation is complete and correct to the best of my knowledge.</p>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Enter your Student ID number to validate this declaration:</label>
                        <input type="text" name="declaration_student_id" value="{{ old('declaration_student_id') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('student.dashboard') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" class="bg-red-800 text-white px-8 py-3 rounded-lg hover:bg-red-900 transition font-semibold">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Show/hide medical certificate section based on disability checkbox
    document.addEventListener('DOMContentLoaded', function() {
        const disabilityCheckbox = document.querySelector('input[name="has_disability"]');
        if (disabilityCheckbox) {
            disabilityCheckbox.addEventListener('change', function() {
                const medicalSection = document.getElementById('medical_certificate_section');
                if (this.checked) {
                    medicalSection.classList.remove('hidden');
                } else {
                    medicalSection.classList.add('hidden');
                }
            });
        }

        // File upload preview
        const medicalFile = document.getElementById('medical_certificate');
        if (medicalFile) {
            medicalFile.addEventListener('change', function(e) {
                const preview = document.getElementById('medical_preview');
                const nameSpan = document.getElementById('medical_name');
                
                if (this.files && this.files[0]) {
                    nameSpan.textContent = this.files[0].name;
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }
            });
        }
    });
</script>
@endsection