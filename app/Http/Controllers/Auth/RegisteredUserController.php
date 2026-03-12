<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentDocument;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Base validation for all users
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,landlord'],
        ]);

        // Student-specific validation
        if ($request->role === 'student') {
            $request->validate([
                'student_id'          => 'required|string|max:20|unique:users,student_id',
                'surname'             => 'required|string|max:255',
                'acceptance_letter'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'proof_of_registration' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'passport'            => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
        }

        // Landlord-specific validation
        if ($request->role === 'landlord') {
            $request->validate([
                'company_name'      => 'required|string|max:255',
                'phone'             => 'required|string|max:20',
                'lease_agreement'   => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'identity_document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);
        }

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'document_status' => $request->role === 'student' ? 'pending' : 'verified',
        ];

        // Add student-specific fields
        if ($request->role === 'student') {
            $userData['student_id'] = $request->student_id;
            $userData['surname']    = $request->surname;
        }

        // Add landlord-specific fields
        if ($request->role === 'landlord') {
            $userData['company_name'] = $request->company_name;
            $userData['phone'] = $request->phone;
        }

        // Create the user
        $user = User::create($userData);

        // Handle student document uploads
        if ($request->role === 'student') {
            $this->uploadStudentDocument($request, $user, 'acceptance_letter');
            $this->uploadStudentDocument($request, $user, 'proof_of_registration');
            $this->uploadStudentDocument($request, $user, 'passport');
        }

        // Handle landlord document uploads
        if ($request->role === 'landlord') {
            $this->uploadStudentDocument($request, $user, 'lease_agreement');
            $this->uploadStudentDocument($request, $user, 'identity_document');
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on role - CHANGED from student.home to student.dashboard
        if ($request->role === 'student') {
            return redirect()->route('student.dashboard')->with('success', 'Registration successful! Your documents are pending verification.');
        } else {
            return redirect()->route('landlord.dashboard')->with('success', 'Registration successful! Welcome to UB Onboarding.');
        }
    }

    /**
     * Upload student document
     */
    private function uploadStudentDocument(Request $request, User $user, string $type): void
    {
        if ($request->hasFile($type)) {
            try {
                $file = $request->file($type);
                
                // Store the file
                $path = $file->store('documents/' . $user->id, 'public');
                
                // Create document record
                StudentDocument::create([
                    'user_id' => $user->id,
                    'document_type' => $type,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'status' => 'pending',
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Document upload failed for ' . $type . ': ' . $e->getMessage());
            }
        }
    }
}