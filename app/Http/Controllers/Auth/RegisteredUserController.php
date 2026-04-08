<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LandlordVerificationDocument;
use App\Models\StudentDocument;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,landlord'],
        ]);

        if ($request->role === 'student') {
            $this->validateStudentRegistration($request);
        }

        if ($request->role === 'landlord') {
            $this->validateLandlordRegistration($request);
        }

        $user = User::create($this->buildUserData($request));

        if ($request->role === 'student') {
            $this->uploadStudentDocument($request, $user, 'acceptance_letter');
            $this->uploadStudentDocument($request, $user, 'proof_of_registration');

            if ($request->student_category === 'international') {
                $this->uploadStudentDocument($request, $user, 'passport');
            }
        }

        if ($request->role === 'landlord') {
            $this->uploadLandlordDocument($request, $user, 'company_registration', 'company_registration_document');
            $this->uploadLandlordDocument($request, $user, 'tax_clearance', 'tax_clearance_certificate');
            $this->uploadLandlordDocument($request, $user, 'identity_document', 'identity_document');
            $this->uploadLandlordDocument($request, $user, 'property_ownership', 'property_ownership_document');
            $this->notifyAdminsAboutLandlordRegistration($user);
        }

        event(new Registered($user));
        Auth::login($user);

        if ($request->role === 'student') {
            return redirect()
                ->route('student.dashboard')
                ->with('success', 'Registration successful. You can now apply for accommodation and track onboarding support from your dashboard.');
        }

        return redirect()
            ->route('landlord.verification')
            ->with('success', 'Registration successful. Complete verification and wait for approval before listing accommodation.');
    }

    private function validateStudentRegistration(Request $request): void
    {
        $request->validate([
            'student_id' => 'required|string|max:20|unique:users,student_id',
            'surname' => 'required|string|max:255',
            'student_category' => 'required|in:local,international',
            'nationality' => 'required|string|max:255',
            'country_of_origin' => 'nullable|string|max:255',
            'passport_number' => 'required_if:student_category,international|nullable|string|max:100',
            'immigration_status' => 'nullable|string|max:255',
            'acceptance_letter' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'proof_of_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'passport' => 'required_if:student_category,international|nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);
    }

    private function validateLandlordRegistration(Request $request): void
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'company_registration_number' => 'required|string|max:100',
            'tax_identification_number' => 'required|string|max:100',
            'company_registration_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'tax_clearance_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'identity_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'property_ownership_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);
    }

    private function buildUserData(Request $request): array
    {
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ];

        if ($request->role === 'student') {
            $userData += [
                'student_id' => $request->student_id,
                'surname' => $request->surname,
                'student_category' => $request->student_category,
                'nationality' => $request->nationality,
                'country_of_origin' => $request->country_of_origin ?: $request->nationality,
                'passport_number' => $request->passport_number,
                'immigration_status' => $request->immigration_status,
                'document_status' => 'pending',
            ];
        }

        if ($request->role === 'landlord') {
            $userData += [
                'company_name' => $request->company_name,
                'phone' => $request->phone,
                'company_registration_number' => $request->company_registration_number,
                'tax_identification_number' => $request->tax_identification_number,
                'landlord_verification_status' => 'pending',
                'landlord_verification_stage' => 'company_registration',
                'landlord_verification_submitted_at' => now(),
            ];
        }

        return $userData;
    }

    private function uploadStudentDocument(Request $request, User $user, string $type): void
    {
        if (!$request->hasFile($type)) {
            return;
        }

        $file = $request->file($type);
        $path = $file->store('documents/' . $user->id, 'public');

        StudentDocument::create([
            'user_id' => $user->id,
            'document_type' => $type,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);
    }

    private function uploadLandlordDocument(Request $request, User $user, string $documentType, string $field): void
    {
        if (!$request->hasFile($field)) {
            return;
        }

        $file = $request->file($field);
        $path = $file->store('landlord-documents/' . $user->id, 'public');

        LandlordVerificationDocument::create([
            'user_id' => $user->id,
            'document_type' => $documentType,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);
    }

    private function notifyAdminsAboutLandlordRegistration(User $landlord): void
    {
        User::where('role', 'admin')
            ->pluck('id')
            ->each(function (int $adminId) use ($landlord) {
                SystemNotification::notifyUser(
                    $adminId,
                    'New landlord registration pending verification',
                    ($landlord->company_name ?? $landlord->name) . ' submitted documents for admin review.',
                    route('admin.landlords.verifications'),
                    'warning',
                    $landlord->id
                );
            });
    }
}
