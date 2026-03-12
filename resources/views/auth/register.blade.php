@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 auth-gradient">
    <div class="max-w-2xl w-full auth-card rounded-xl shadow-2xl p-8">
        <div class="text-center mb-8">
            <i class="fas fa-user-plus text-5xl text-red-800 mb-4"></i>
            <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
            <p class="text-gray-600 mt-2">Join UB Onboarding System</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="registrationForm">
            @csrf

            <!-- STEP 1: Account Type -->
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Account Type</h3>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="role" value="student" id="role_student"
                               class="rounded border-gray-300 text-red-800 focus:ring-red-800" checked>
                        <span class="ml-2 text-gray-700 font-medium">Student</span>
                    </label>
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="radio" name="role" value="landlord" id="role_landlord"
                               class="rounded border-gray-300 text-red-800 focus:ring-red-800">
                        <span class="ml-2 text-gray-700 font-medium">Landlord / Property Owner</span>
                    </label>
                </div>
                @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- STEP 2: Personal Information -->
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Personal Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-600">*</span>
                        </label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('name') border-red-500 @enderror"
                               placeholder="Your full name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Student ID (students only) -->
                    <div id="field_student_id" class="student-only">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Student ID <span class="text-red-600">*</span>
                        </label>
                        <input id="student_id" type="text" name="student_id" value="{{ old('student_id') }}"
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('student_id') border-red-500 @enderror"
                               placeholder="e.g., 201905436">
                        @error('student_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Surname (students only) -->
                    <div id="field_surname" class="student-only">
                        <label for="surname" class="block text-sm font-medium text-gray-700 mb-2">
                            Surname <span class="text-red-600">*</span>
                        </label>
                        <input id="surname" type="text" name="surname" value="{{ old('surname') }}"
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('surname') border-red-500 @enderror"
                               placeholder="Your surname">
                        @error('surname')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name (landlords only) -->
                    <div id="field_company" class="landlord-only" style="display:none">
                        <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Company Name <span class="text-red-600">*</span>
                        </label>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}"
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('company_name') border-red-500 @enderror"
                               placeholder="Your company name">
                        @error('company_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone (landlords only) -->
                    <div id="field_phone" class="landlord-only" style="display:none">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number <span class="text-red-600">*</span>
                        </label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}"
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('phone') border-red-500 @enderror"
                               placeholder="+267 71 234 567">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- STEP 3: Account Security -->
            <div class="mb-6 border-b border-gray-200 pb-4">
                <h3 class="text-lg font-semibold text-red-800 mb-4">Account Security</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-600">*</span>
                        </label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('email') border-red-500 @enderror"
                               placeholder="201905436@ub.ac.bw">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-600">*</span>
                        </label>
                        <input id="password" type="password" name="password" required
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800 @error('password') border-red-500 @enderror"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password <span class="text-red-600">*</span>
                        </label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="auth-input w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-red-800 focus:ring-red-800"
                               placeholder="••••••••">
                    </div>
                </div>
            </div>

            <!-- STEP 4: Student Documents (students only) -->
            <div id="student-documents-section" class="bg-gray-50 rounded-xl p-6 mb-6">
                <div class="flex items-center mb-4">
                    <div class="w-8 h-8 bg-red-800 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-file-alt text-white text-sm"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Required Documents</h3>
                </div>

                <div class="space-y-5">
                    <!-- Acceptance Letter -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-red-300 transition">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                            Acceptance Letter <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="file" name="acceptance_letter" id="acceptance_letter"
                                   accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                            <button type="button" onclick="document.getElementById('acceptance_letter').click()"
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-upload mr-2"></i>Choose File
                            </button>
                            <span id="acceptance_file_name" class="text-sm text-gray-500">No file chosen</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">PDF, JPG or PNG (Max 2MB)</p>
                        @error('acceptance_letter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Proof of Registration -->
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-red-300 transition">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                            Proof of Registration <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="file" name="proof_of_registration" id="proof_of_registration"
                                   accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                            <button type="button" onclick="document.getElementById('proof_of_registration').click()"
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-upload mr-2"></i>Choose File
                            </button>
                            <span id="proof_file_name" class="text-sm text-gray-500">No file chosen</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">PDF, JPG or PNG (Max 2MB)</p>
                        @error('proof_of_registration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Passport (all students) -->
                    <div id="passport-section" class="border-2 border-dashed border-gray-300 rounded-xl p-5 hover:border-red-300 transition">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            <i class="fas fa-passport text-red-600 mr-2"></i>
                            Passport Copy <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="file" name="passport" id="passport"
                                   accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                            <button type="button" onclick="document.getElementById('passport').click()"
                                    class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center">
                                <i class="fas fa-upload mr-2"></i>Choose File
                            </button>
                            <span id="passport_file_name" class="text-sm text-gray-500">No file chosen</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">PDF, JPG or PNG (Max 2MB)</p>
                        @error('passport')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 p-3 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                    <div class="flex">
                        <i class="fas fa-info-circle text-yellow-600 mr-2 mt-0.5"></i>
                        <p class="text-sm text-gray-700">
                            Your documents will be verified by the Welfare Office before you can apply for on-campus accommodation. This usually takes 1–2 business days.
                        </p>
                    </div>
                </div>
            </div>

            <button type="submit" class="auth-button w-full text-white font-semibold py-3 px-4 rounded-lg transition">
                Create Account
            </button>

            <p class="text-center mt-6 text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="auth-link font-semibold hover:underline">Sign in here</a>
            </p>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const studentRadio  = document.getElementById('role_student');
    const landlordRadio = document.getElementById('role_landlord');
    const docsSection   = document.getElementById('student-documents-section');
    const passportSec   = document.getElementById('passport-section');
    const emailInput    = document.getElementById('email');

    const studentIdInput  = document.getElementById('student_id');
    const surnameInput    = document.getElementById('surname');
    const companyInput    = document.getElementById('company_name');
    const phoneInput      = document.getElementById('phone');
    const acceptanceInput = document.getElementById('acceptance_letter');
    const proofInput      = document.getElementById('proof_of_registration');
    const passportInput   = document.getElementById('passport');

    function setRequired(el, val) {
        if (!el) return;
        if (val) el.setAttribute('required', 'required');
        else el.removeAttribute('required');
    }

    function show(el) { if (el) el.style.display = 'block'; }
    function hide(el) { if (el) el.style.display = 'none'; }

    function toggleRole() {
        const isStudent = studentRadio.checked;

        // Student-only fields
        document.querySelectorAll('.student-only').forEach(el => {
            isStudent ? show(el) : hide(el);
        });
        // Landlord-only fields
        document.querySelectorAll('.landlord-only').forEach(el => {
            isStudent ? hide(el) : show(el);
        });

        // Documents section (students only)
        if (isStudent) show(docsSection); else hide(docsSection);

        // Passport always shown for students
        if (isStudent) { show(passportSec); setRequired(passportInput, true); }
        else           { hide(passportSec); setRequired(passportInput, false); }

        // Required fields
        setRequired(studentIdInput,  isStudent);
        setRequired(surnameInput,    isStudent);
        setRequired(companyInput,    !isStudent);
        setRequired(phoneInput,      !isStudent);
        setRequired(acceptanceInput, isStudent);
        setRequired(proofInput,      isStudent);

        // Email placeholder
        emailInput.placeholder = isStudent ? '201905436@ub.ac.bw' : 'your@email.com';
    }

    studentRadio.addEventListener('change', toggleRole);
    landlordRadio.addEventListener('change', toggleRole);

    // File name display
    [
        ['acceptance_letter',    'acceptance_file_name'],
        ['proof_of_registration','proof_file_name'],
        ['passport',             'passport_file_name'],
    ].forEach(function([inputId, spanId]) {
        var inp  = document.getElementById(inputId);
        var span = document.getElementById(spanId);
        if (inp && span) {
            inp.addEventListener('change', function () {
                span.textContent = this.files[0] ? this.files[0].name : 'No file chosen';
            });
        }
    });

    // Set initial state on page load
    toggleRole();
});
</script>
@endsection
