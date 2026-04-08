@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-4xl w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-user-plus text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
            <p class="text-gray-600 mt-2">Student onboarding, verified housing, and support in one portal.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm" class="space-y-8">
            @csrf

            <section class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Account Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="border rounded-xl p-4 cursor-pointer bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Student</p>
                                <p class="text-sm text-gray-600">Apply for accommodation and track onboarding help.</p>
                            </div>
                            <input type="radio" name="role" value="student" class="text-red-800 focus:ring-red-800" {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                        </div>
                    </label>
                    <label class="border rounded-xl p-4 cursor-pointer bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Landlord</p>
                                <p class="text-sm text-gray-600">Advertise verified off-campus accommodation.</p>
                            </div>
                            <input type="radio" name="role" value="landlord" class="text-red-800 focus:ring-red-800" {{ old('role') === 'landlord' ? 'checked' : '' }}>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </section>

            <section class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Basic Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800" placeholder="Your full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800" placeholder="name@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input id="password" type="password" name="password" required class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800">
                    </div>
                </div>
            </section>

            <section id="student-fields" class="space-y-6">
                <div class="border rounded-xl p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student ID</label>
                            <input type="text" name="student_id" value="{{ old('student_id') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="201905436">
                            @error('student_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Surname</label>
                            <input type="text" name="surname" value="{{ old('surname') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300">
                            @error('surname')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Student Category</label>
                            <select name="student_category" id="student_category" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300">
                                <option value="local" {{ old('student_category', 'local') === 'local' ? 'selected' : '' }}>Local</option>
                                <option value="international" {{ old('student_category') === 'international' ? 'selected' : '' }}>International</option>
                            </select>
                            @error('student_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Botswana">
                            @error('nationality')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="country_of_origin_wrap">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Country of Origin</label>
                            <input type="text" name="country_of_origin" value="{{ old('country_of_origin') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Country of origin">
                            @error('country_of_origin')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="passport_number_wrap">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passport Number</label>
                            <input type="text" name="passport_number" value="{{ old('passport_number') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Passport number">
                            @error('passport_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="md:col-span-2" id="immigration_status_wrap">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Immigration Status</label>
                            <input type="text" name="immigration_status" value="{{ old('immigration_status') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Study permit, visa stage, or related notes">
                            @error('immigration_status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border rounded-xl p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Documents</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Acceptance Letter</label>
                            <input type="file" name="acceptance_letter" class="block w-full text-sm text-gray-700">
                            @error('acceptance_letter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Registration</label>
                            <input type="file" name="proof_of_registration" class="block w-full text-sm text-gray-700">
                            @error('proof_of_registration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="passport_upload_wrap">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Passport Copy</label>
                            <input type="file" name="passport" class="block w-full text-sm text-gray-700">
                            @error('passport')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <section id="landlord-fields" class="space-y-6" style="display:none">
                <div class="border rounded-xl p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Landlord Profile</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300">
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="+267 71 234 567">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Registration Number</label>
                            <input type="text" name="company_registration_number" value="{{ old('company_registration_number') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300">
                            @error('company_registration_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Identification Number</label>
                            <input type="text" name="tax_identification_number" value="{{ old('tax_identification_number') }}" class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300">
                            @error('tax_identification_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border rounded-xl p-6 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Documents</h3>
                    <p class="text-sm text-gray-600 mb-4">Listings become available only after admin review of company registration, tax clearance, director or signatory ID, and property ownership documents.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Company Registration</label>
                            <input type="file" name="company_registration_document" class="block w-full text-sm text-gray-700">
                            @error('company_registration_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tax Clearance Certificate</label>
                            <input type="file" name="tax_clearance_certificate" class="block w-full text-sm text-gray-700">
                            @error('tax_clearance_certificate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Director / Signatory ID</label>
                            <input type="file" name="identity_document" class="block w-full text-sm text-gray-700">
                            @error('identity_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Ownership Document</label>
                            <input type="file" name="property_ownership_document" class="block w-full text-sm text-gray-700">
                            @error('property_ownership_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-red-800">Already have an account?</a>
                <button type="submit" class="auth-button text-white px-6 py-3 rounded-lg font-semibold">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const roleInputs = document.querySelectorAll('input[name="role"]');
    const studentFields = document.getElementById('student-fields');
    const landlordFields = document.getElementById('landlord-fields');
    const studentCategory = document.getElementById('student_category');
    const passportWrap = document.getElementById('passport_upload_wrap');
    const passportNumberWrap = document.getElementById('passport_number_wrap');
    const immigrationStatusWrap = document.getElementById('immigration_status_wrap');

    function toggleRoleSections() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        const showStudent = selectedRole === 'student';

        studentFields.style.display = showStudent ? 'block' : 'none';
        landlordFields.style.display = showStudent ? 'none' : 'block';
    }

    function toggleInternationalFields() {
        const international = studentCategory.value === 'international';
        passportWrap.style.display = international ? 'block' : 'none';
        passportNumberWrap.style.display = international ? 'block' : 'none';
        immigrationStatusWrap.style.display = international ? 'block' : 'none';
    }

    roleInputs.forEach((input) => input.addEventListener('change', toggleRoleSections));
    studentCategory.addEventListener('change', toggleInternationalFields);

    toggleRoleSections();
    toggleInternationalFields();
</script>
@endsection
