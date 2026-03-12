@extends('layouts.app')

@section('title', 'Accommodation Application Form')

@section('header')
    <h2 class="font-semibold text-xl text-white leading-tight">
        Accommodation Application Form
    </h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <!-- Header -->
            <div class="bg-red-800 text-white px-6 py-4">
                <h1 class="text-2xl font-bold">Department of Student Welfare</h1>
                <h2 class="text-xl mt-2">Returning Students application for accommodation in Residence halls</h2>
            </div>
            
            <!-- Instructions -->
            <div class="p-6 bg-yellow-50 border-l-4 border-yellow-400">
                <p class="font-bold text-red-800 mb-2">PLEASE READ CAREFULLY</p>
                <ul class="list-disc list-inside space-y-1 text-sm text-gray-700">
                    <li>All Students interested in securing on campus accommodation must complete this application and submit it to the Accommodation Office not later than the specified date.</li>
                    <li class="font-bold text-red-600">LATE APPLICATIONS WILL BE REJECTED.</li>
                    <li class="font-bold">AN APPLICATION IS NOT A GUARANTEE FOR A PLACE IN RESIDENCES.</li>
                    <li class="font-bold">ALL INFORMATION NEEDS TO BE FILLED OUT COMPLETELY.</li>
                    <li class="font-bold">COMPLETE IN CAPITAL LETTERS:</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('student.accommodations.apply') }}" enctype="multipart/form-data" class="p-6 space-y-8">
                @csrf

                <!-- 1) Personal Details -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">1) Personal Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Student ID:</label>
                            <input type="text" name="student_id" value="{{ old('student_id', Auth::user()->student_id ?? '') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="e.g., 201905436" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Surname:</label>
                            <input type="text" name="surname" value="{{ old('surname', Auth::user()->surname ?? '') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">First Name:</label>
                            <input type="text" name="first_name" value="{{ old('first_name', Auth::user()->name ?? '') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Gender:</label>
                            <select name="gender" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- 2) Contact Number -->
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
                            <p class="text-xs text-gray-600 mt-1">N.B. The university email address is the official means of communication from the University of Botswana. Please be sure you are checking your account regularly to assure your prompt response to any inquiries.</p>
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

                <!-- 6) Emergency Contact/Next of Kin -->
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
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Address:</label>
                            <input type="text" name="emergency_address" value="{{ old('emergency_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase" required>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email:</label>
                            <input type="email" name="emergency_email" value="{{ old('emergency_email') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                </div>

                <!-- 7) Father's Details -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">7) Father's Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name:</label>
                            <input type="text" name="father_name" value="{{ old('father_name') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Occupation:</label>
                            <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="father_telephone" value="{{ old('father_telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="father_mobile" value="{{ old('father_mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                    
                    <h4 class="font-bold mt-4 mb-2">7a) Home Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="father_address" value="{{ old('father_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Street/Box, City, Country">
                        </div>
                    </div>
                </div>

                <!-- 8) Mother's Details -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">8) Mother's Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name:</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Occupation:</label>
                            <input type="text" name="mother_occupation" value="{{ old('mother_occupation') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="mother_telephone" value="{{ old('mother_telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="mother_mobile" value="{{ old('mother_mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                    
                    <h4 class="font-bold mt-4 mb-2">8a) Home Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="mother_address" value="{{ old('mother_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Street/Box, City, Country">
                        </div>
                    </div>
                </div>

                <!-- 9) Guardian's Details (if applicable) -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">9) Guardian's Details (if applicable)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name:</label>
                            <input type="text" name="guardian_name" value="{{ old('guardian_name') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Relationship:</label>
                            <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="guardian_telephone" value="{{ old('guardian_telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="guardian_mobile" value="{{ old('guardian_mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                    
                    <h4 class="font-bold mt-4 mb-2">9a) Home Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="guardian_address" value="{{ old('guardian_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Street/Box, City, Country">
                        </div>
                    </div>

                    <h4 class="font-bold mt-4 mb-2">9b) Work Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="guardian_work_address" value="{{ old('guardian_work_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Employer, Work Address">
                        </div>
                    </div>
                </div>

                <!-- 10) Spouse's Details (For married persons only) -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">10) Spouse's Details (For married persons only)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Full Name:</label>
                            <input type="text" name="spouse_name" value="{{ old('spouse_name') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Occupation:</label>
                            <input type="text" name="spouse_occupation" value="{{ old('spouse_occupation') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Telephone:</label>
                            <input type="tel" name="spouse_telephone" value="{{ old('spouse_telephone') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Mobile:</label>
                            <input type="tel" name="spouse_mobile" value="{{ old('spouse_mobile') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800">
                        </div>
                    </div>
                    
                    <h4 class="font-bold mt-4 mb-2">10a) Home Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="spouse_address" value="{{ old('spouse_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Street/Box, City, Country">
                        </div>
                    </div>

                    <h4 class="font-bold mt-4 mb-2">10b) Work Address:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <input type="text" name="spouse_work_address" value="{{ old('spouse_work_address') }}" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                                   placeholder="Employer, Work Address">
                        </div>
                    </div>
                </div>

                <!-- 11) Specific Roommate Request -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">11) Specific Roommate Request</h3>
                    
                    <p class="text-sm text-gray-700 mb-4">Enter the full name of the student you would like to live with - we will do our best to assign you together. NOTE - they should also submit your name.</p>
                    
                    <div>
                        <input type="text" name="requested_roommate" value="{{ old('requested_roommate') }}" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-red-800 focus:ring-red-800 uppercase"
                               placeholder="Full name of requested roommate">
                    </div>
                </div>

                <!-- 12) Roommate Matching Conditions -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">12) Roommate Matching Conditions</h3>
                    
                    <p class="text-sm text-gray-700 mb-4">For ad hoc roommate matching, please answer each question below and mark one and only one as critical by ticking the adjoining box.</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border p-3 text-left">Are you...</th>
                                    <th class="border p-3 text-center">Answer</th>
                                    <th class="border p-3 text-center">Critical</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $conditions = [
                                        'Undergraduate Year 1 Students' => 'year1',
                                        'Undergraduate Year 2 Students' => 'year2',
                                        'Undergraduate Year 3 students' => 'year3',
                                        'Undergraduate Final Year Students' => 'final_year',
                                        'Postgraduate Students' => 'postgraduate'
                                    ];
                                @endphp
                                @foreach($conditions as $label => $field)
                                <tr>
                                    <td class="border p-3">{{ $label }}</td>
                                    <td class="border p-3 text-center">
                                        <select name="match_{{ $field }}" class="border rounded px-2 py-1">
                                            <option value="">Select</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </td>
                                    <td class="border p-3 text-center">
                                        <input type="checkbox" name="critical_{{ $field }}" value="1" class="rounded border-gray-300 text-red-800">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 13) Reasons for Applying -->
                <div class="border-2 border-gray-300 p-6 rounded-lg">
                    <h3 class="text-xl font-bold bg-red-800 text-white -mt-8 -ml-2 px-4 py-2 rounded-t-lg inline-block">13) Reasons for Applying for Accommodation</h3>
                    
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

                <!-- Medical Certificate Upload (shown only if disability checked) -->
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

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('student.home') }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 transition">
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

        // File upload preview for medical certificate
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