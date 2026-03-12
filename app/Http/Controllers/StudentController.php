<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\Application;
use App\Models\Property;
use App\Models\ViewingRequest;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Student Controller
 * 
 * Handles all student functionality in the accommodation system.
 */
class StudentController extends Controller
{
    /**
     * Student home page - aliases to dashboard
     */
    public function home()
    {
        return $this->dashboard();
    }

    /**
     * Display student dashboard with overview of applications, viewings, and payments
     */
    public function dashboard()
    {
        $student = Auth::user();
        
        if (!$student) {
            return redirect()->route('login');
        }
        
        $recentApplications = Application::where('student_id', $student->id)
            ->with('accommodation')
            ->latest()
            ->take(5)
            ->get();
        
        $recentViewings = ViewingRequest::where('student_id', $student->id)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();
        
        $recentPayments = Payment::where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();
        
        $stats = [
            'total_applications' => Application::where('student_id', $student->id)->count(),
            'pending_applications' => Application::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count(),
            'approved_applications' => Application::where('student_id', $student->id)
                ->where('status', 'approved')
                ->count(),
            'rejected_applications' => Application::where('student_id', $student->id)
                ->where('status', 'rejected')
                ->count(),
            'total_viewings' => ViewingRequest::where('student_id', $student->id)->count(),
            'pending_viewings' => ViewingRequest::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count(),
            'approved_viewings' => ViewingRequest::where('student_id', $student->id)
                ->where('status', 'approved')
                ->count(),
            'upcoming_viewings' => ViewingRequest::where('student_id', $student->id)
                ->where('status', 'approved')
                ->where('scheduled_date', '>=', now())
                ->count(),
            'total_payments' => Payment::where('student_id', $student->id)->count(),
            'completed_payments' => Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->count(),
            'pending_payments' => Payment::where('student_id', $student->id)
                ->where('status', 'pending')
                ->count(),
            'total_spent' => Payment::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('amount'),
        ];
        
        return view('student.dashboard', compact('recentApplications', 'recentViewings', 'recentPayments', 'stats'));
    }

    /**
     * Display all available on-campus accommodations
     */
    public function accommodations(Request $request)
    {
        $query = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity');
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }
        
        if ($request->filled('block')) {
            $query->where('block', $request->block);
        }
        
        $types = Accommodation::select('type')->distinct()->pluck('type');
        $blocks = Accommodation::select('block')->distinct()->whereNotNull('block')->pluck('block');
        
        $accommodations = $query->paginate(9);
        
        return view('student.accommodations', compact('accommodations', 'types', 'blocks'));
    }

    /**
     * Show the accommodation application form
     */
    public function showApplicationForm()
    {
        return view('student.accommodation-application-form');
    }

    /**
     * Store a general accommodation application (no specific room pre-selected)
     */
    public function storeApplication(Request $request)
    {
        $request->validate([
            'student_id'         => 'required|string|max:20',
            'surname'            => 'required|string|max:255',
            'first_name'         => 'required|string|max:255',
            'gender'             => 'required|in:Male,Female,Other',
            'mobile'             => 'required|string|max:20',
            'university_email'   => 'required|email|max:255',
            'emergency_name'     => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:100',
            'emergency_telephone' => 'required|string|max:20',
            'emergency_address'  => 'required|string|max:500',
            'reasons'            => 'required|string|max:2000',
            'declaration_student_id' => 'required|string|max:20',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Block duplicate pending applications
        $existing = Application::where('student_id', Auth::id())
            ->whereIn('status', ['pending', 'waitlisted'])
            ->whereNull('accommodation_id')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending accommodation application.');
        }

        // Handle medical certificate upload
        $medicalCertPath = null;
        if ($request->hasFile('medical_certificate')) {
            $medicalCertPath = $request->file('medical_certificate')
                ->store('medical_certificates/' . Auth::id(), 'public');
        }

        // Collect all form fields into form_data JSON
        $formData = $request->except(['_token', 'medical_certificate']);

        Application::create([
            'student_id'         => Auth::id(),
            'accommodation_id'   => null,
            'status'             => 'pending',
            'special_requirements' => $request->reasons,
            'has_disability'     => $request->boolean('has_disability'),
            'medical_certificate' => $medicalCertPath,
            'form_data'          => $formData,
        ]);

        return redirect()->route('student.applications')
            ->with('success', 'Your accommodation application has been submitted successfully! The Welfare Office will review it shortly.');
    }

    /**
     * Show single accommodation details
     */
    public function showAccommodation(Accommodation $accommodation)
    {
        if (!$accommodation->is_available || $accommodation->current_occupancy >= $accommodation->capacity) {
            return redirect()->route('student.accommodations')
                ->with('error', 'This accommodation is no longer available.');
        }
        
        return view('student.accommodation-show', compact('accommodation'));
    }

    /**
     * Apply for on-campus accommodation
     */
    public function apply(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'preferred_move_in_date' => 'required|date|after:today',
            'duration_months' => 'required|integer|min:6|max:36',
            'special_requirements' => 'nullable|string|max:500',
        ]);

        if (!$accommodation->hasSpace()) {
            return back()->with('error', 'Sorry, this accommodation is no longer available.');
        }

        $existingApplication = Application::where('student_id', Auth::id())
            ->where('accommodation_id', $accommodation->id)
            ->whereIn('status', ['pending', 'waitlisted'])
            ->first();

        if ($existingApplication) {
            return back()->with('error', 'You already have a pending application for this accommodation.');
        }

        DB::beginTransaction();

        try {
            $application = Application::create([
                'student_id' => Auth::id(),
                'accommodation_id' => $accommodation->id,
                'preferred_move_in_date' => $request->preferred_move_in_date,
                'duration_months' => $request->duration_months,
                'special_requirements' => $request->special_requirements,
                'status' => 'pending',
            ]);

            Payment::create([
                'student_id' => Auth::id(),
                'payable_type' => Application::class,
                'payable_id' => $application->id,
                'amount' => 50.00,
                'type' => 'application_fee',
                'status' => 'pending',
                'payment_method' => 'online',
            ]);

            DB::commit();

            return redirect()->route('student.applications')
                ->with('success', 'Application submitted successfully! Please pay the application fee to complete the process.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error submitting your application. Please try again.');
        }
    }

    /**
     * Display student's applications
     */
    public function applications()
    {
        $applications = Application::where('student_id', Auth::id())
            ->with(['accommodation', 'payment'])
            ->latest()
            ->paginate(10);
        
        return view('student.applications', compact('applications'));
    }

    /**
     * Show single application details
     */
    public function showApplication(Application $application)
    {
        if ($application->student_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $application->load(['accommodation', 'payment', 'processor']);
        
        return view('student.application-show', compact('application'));
    }

    /**
     * Display off-campus properties
     */
    public function properties(Request $request)
    {
        $query = Property::where('is_approved', true)->where('is_available', true);
        
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('min_price')) {
            $query->where('monthly_rent', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }
        
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }
        
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('monthly_rent', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('monthly_rent', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                case 'nearest':
                    $query->orderBy('distance_to_campus_km', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $cities = Property::where('is_approved', true)->select('city')->distinct()->pluck('city');
        $types = Property::where('is_approved', true)->select('type')->distinct()->pluck('type');
        
        $properties = $query->paginate(12)->withQueryString();
        
        return view('student.properties', compact('properties', 'cities', 'types'));
    }

    /**
     * Show single property details
     */
    public function showProperty(Property $property)
    {
        if (!$property->is_approved || !$property->is_available) {
            abort(404, 'Property not found or not available.');
        }
        
        $landlord = $property->landlord;
        
        $existingRequest = null;
        if (Auth::check()) {
            $existingRequest = ViewingRequest::where('student_id', Auth::id())
                ->where('property_id', $property->id)
                ->first();
        }
        
        return view('student.property-show', compact('property', 'landlord', 'existingRequest'));
    }

    /**
     * Request a property viewing
     */
    public function requestViewing(Request $request, Property $property)
    {
        $request->validate([
            'preferred_date' => 'required|date|after:today',
            'message' => 'nullable|string|max:500',
        ]);

        if (!$property->is_available) {
            return back()->with('error', 'This property is not available for viewing.');
        }

        $existingRequest = ViewingRequest::where('student_id', Auth::id())
            ->where('property_id', $property->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending viewing request for this property.');
        }

        ViewingRequest::create([
            'student_id' => Auth::id(),
            'property_id' => $property->id,
            'landlord_id' => $property->landlord_id,
            'preferred_date' => $request->preferred_date,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        return redirect()->route('student.viewing-requests')
            ->with('success', 'Viewing request sent to landlord. You will be notified when they respond.');
    }

    /**
     * Display student's viewing requests
     */
    public function viewingRequests()
    {
        $requests = ViewingRequest::where('student_id', Auth::id())
            ->with(['property', 'landlord'])
            ->latest()
            ->paginate(10);
        
        return view('student.viewing-requests', compact('requests'));
    }

    /**
     * Display student's payment history
     */
    public function payments()
    {
        $payments = Payment::where('student_id', Auth::id())
            ->with('payable')
            ->latest()
            ->paginate(10);
        
        $totalPaid = Payment::where('student_id', Auth::id())
            ->where('status', 'completed')
            ->sum('amount');
        
        $pendingPayments = Payment::where('student_id', Auth::id())
            ->where('status', 'pending')
            ->sum('amount');
        
        return view('student.payments', compact('payments', 'totalPaid', 'pendingPayments'));
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'payment_method' => 'required|in:card,bank_transfer',
        ]);

        $payment = Payment::where('id', $request->payment_id)
            ->where('student_id', Auth::id())
            ->firstOrFail();

        $payment->markAsCompleted('TXN_' . strtoupper(uniqid()));

        return redirect()->route('student.payments')
            ->with('success', 'Payment processed successfully!');
    }
}