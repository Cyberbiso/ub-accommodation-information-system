<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Application;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyBooking;
use App\Models\PropertyEnquiry;
use App\Models\SupportRequest;
use App\Models\SystemNotification;
use App\Models\User;
use App\Models\ViewingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function home()
    {
        return $this->dashboard();
    }

    public function dashboard()
    {
        $student = Auth::user();

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
            ->with('payable')
            ->latest()
            ->take(5)
            ->get();

        $recentBookings = PropertyBooking::where('student_id', $student->id)
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        $supportRequests = SupportRequest::where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $recentEnquiries = PropertyEnquiry::where('student_id', $student->id)
            ->with(['property', 'landlord'])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_applications' => Application::where('student_id', $student->id)->count(),
            'pending_applications' => Application::where('student_id', $student->id)->where('status', 'pending')->count(),
            'approved_applications' => Application::where('student_id', $student->id)->where('status', 'approved')->count(),
            'rejected_applications' => Application::where('student_id', $student->id)->where('status', 'rejected')->count(),
            'total_viewings' => ViewingRequest::where('student_id', $student->id)->count(),
            'upcoming_viewings' => ViewingRequest::where('student_id', $student->id)
                ->where('status', 'approved')
                ->where('scheduled_date', '>=', now())
                ->count(),
            'total_payments' => Payment::where('student_id', $student->id)->count(),
            'completed_payments' => Payment::where('student_id', $student->id)->where('status', 'completed')->count(),
            'pending_payments' => Payment::where('student_id', $student->id)->where('status', 'pending')->count(),
            'total_spent' => Payment::where('student_id', $student->id)->where('status', 'completed')->sum('amount'),
            'off_campus_bookings' => PropertyBooking::where('student_id', $student->id)->count(),
            'open_support_requests' => SupportRequest::where('student_id', $student->id)->whereIn('status', ['open', 'in_progress'])->count(),
            'property_enquiries' => PropertyEnquiry::where('student_id', $student->id)->count(),
        ];

        return view('student.dashboard', compact(
            'recentApplications',
            'recentViewings',
            'recentPayments',
            'recentBookings',
            'supportRequests',
            'recentEnquiries',
            'stats'
        ));
    }

    public function accommodations(Request $request)
    {
        $query = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('min_price')) {
            $query->where('monthly_rent', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }

        if ($request->filled('block')) {
            $query->where('block', $request->block);
        }

        $types = Accommodation::select('type')->distinct()->pluck('type');
        $blocks = Accommodation::select('block')->distinct()->whereNotNull('block')->pluck('block');
        $accommodations = $query->paginate(9)->withQueryString();

        return view('student.on-campus', compact('accommodations', 'types', 'blocks'));
    }

    public function showApplicationForm()
    {
        return view('student.accommodation-application-form');
    }

    public function storeApplication(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|max:20',
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'mobile' => 'required|string|max:20',
            'university_email' => 'required|email|max:255',
            'preferred_move_in_date' => 'required|date|after:today',
            'duration_months' => 'required|integer|in:6,9,12,18,24',
            'emergency_name' => 'required|string|max:255',
            'emergency_relationship' => 'required|string|max:100',
            'emergency_telephone' => 'required|string|max:20',
            'emergency_address' => 'required|string|max:500',
            'reasons' => 'required|string|max:2000',
            'declaration_student_id' => 'required|string|max:20',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $existing = Application::where('student_id', Auth::id())
            ->whereIn('status', ['pending', 'waitlisted'])
            ->whereNull('accommodation_id')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending accommodation application.');
        }

        $medicalCertPath = null;
        if ($request->hasFile('medical_certificate')) {
            $medicalCertPath = $request->file('medical_certificate')
                ->store('medical_certificates/' . Auth::id(), 'public');
        }

        $formData = $request->except(['_token', 'medical_certificate']);

        Application::create([
            'student_id' => Auth::id(),
            'accommodation_id' => null,
            'preferred_move_in_date' => $request->preferred_move_in_date,
            'duration_months' => $request->duration_months,
            'status' => 'pending',
            'special_requirements' => $request->reasons,
            'has_disability' => $request->boolean('has_disability'),
            'medical_certificate' => $medicalCertPath,
            'form_data' => $formData,
        ]);

        return redirect()->route('student.applications')
            ->with('success', 'Your accommodation application has been submitted successfully.');
    }

    public function showAccommodation(Accommodation $accommodation)
    {
        if (!$accommodation->is_available || !$accommodation->hasSpace()) {
            return redirect()->route('student.accommodations')
                ->with('error', 'This accommodation is no longer available.');
        }

        return view('student.accommodation-show', compact('accommodation'));
    }

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

        DB::transaction(function () use ($request, $accommodation) {
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
        });

        return redirect()->route('student.applications')
            ->with('success', 'Application submitted successfully. Please pay the application fee to complete the process.');
    }

    public function applications()
    {
        $applications = Application::where('student_id', Auth::id())
            ->with(['accommodation', 'payment'])
            ->latest()
            ->paginate(10);

        return view('student.applications', compact('applications'));
    }

    public function showApplication(Application $application)
    {
        abort_unless($application->student_id === Auth::id(), 403);

        $application->load(['accommodation', 'payment', 'processor']);

        return view('student.application-details', compact('application'));
    }

    public function properties(Request $request)
    {
        $query = Property::with('landlord')->live();

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

        if ($request->filled('max_distance')) {
            $query->where('distance_to_campus_km', '<=', $request->max_distance);
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('monthly_rent', 'asc'),
                'price_desc' => $query->orderBy('monthly_rent', 'desc'),
                'nearest' => $query->orderBy('distance_to_campus_km', 'asc'),
                default => $query->latest(),
            };
        } else {
            $query->latest();
        }

        $cities = Property::approved()->select('city')->distinct()->pluck('city');
        $types = Property::approved()->select('type')->distinct()->pluck('type');
        $properties = $query->paginate(12)->withQueryString();
        $campus = config('campus');

        return view('student.properties', compact('properties', 'cities', 'types', 'campus'));
    }

    public function showProperty(Property $property)
    {
        $this->ensureApprovedProperty($property, true);

        $property->load('landlord');
        $landlord = $property->landlord;
        $existingRequest = ViewingRequest::where('student_id', Auth::id())
            ->where('property_id', $property->id)
            ->latest()
            ->first();
        $existingBooking = PropertyBooking::where('student_id', Auth::id())
            ->where('property_id', $property->id)
            ->whereIn('status', [
                PropertyBooking::STATUS_PENDING_LANDLORD_REVIEW,
                PropertyBooking::STATUS_APPROVED_AWAITING_LEASE,
                PropertyBooking::STATUS_LEASE_PENDING_LANDLORD_APPROVAL,
                PropertyBooking::STATUS_APPROVED_AWAITING_PAYMENT,
                PropertyBooking::STATUS_CONFIRMED,
            ])
            ->with('payment')
            ->latest()
            ->first();
        $existingEnquiry = PropertyEnquiry::where('student_id', Auth::id())
            ->where('property_id', $property->id)
            ->latest()
            ->first();
        $similarProperties = Property::live()
            ->where('id', '!=', $property->id)
            ->where('city', $property->city)
            ->take(3)
            ->get();
        $campus = config('campus');

        return view('student.property-show', compact(
            'property',
            'landlord',
            'existingRequest',
            'existingBooking',
            'existingEnquiry',
            'similarProperties',
            'campus'
        ));
    }

    public function requestViewing(Request $request, Property $property)
    {
        $this->ensureApprovedProperty($property, true);

        $request->validate([
            'preferred_date' => 'required|date|after:today',
            'message' => 'nullable|string|max:500',
        ]);

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

        SystemNotification::notifyUser(
            $property->landlord_id,
            'New viewing request',
            Auth::user()->name . ' requested to view ' . $property->title . '.',
            route('landlord.viewing-requests'),
            'info',
            Auth::id()
        );

        return redirect()->route('student.viewing-requests')
            ->with('success', 'Viewing request sent to landlord.');
    }

    public function storePropertyBooking(Request $request, Property $property)
    {
        $this->ensureApprovedProperty($property, true);

        $minimumMoveInDate = $property->earliest_move_in_date;

        $request->validate([
            'move_in_date' => 'required|date|after_or_equal:' . $minimumMoveInDate,
            'lease_months' => 'required|integer|min:3|max:24',
            'occupants' => 'required|integer|min:1|max:8',
            'special_requests' => 'nullable|string|max:1000',
        ], [
            'move_in_date.after_or_equal' => 'The move-in date must be on or after ' . \Carbon\Carbon::parse($minimumMoveInDate)->format('d M Y') . '.',
        ]);

        $existingBooking = PropertyBooking::where('student_id', Auth::id())
            ->where('property_id', $property->id)
            ->whereNotIn('status', [PropertyBooking::STATUS_REJECTED])
            ->with('payment')
            ->first();

        if ($existingBooking) {
            $message = $existingBooking->isConfirmed()
                ? 'You have already booked this property.'
                : 'You already have an active booking request for this property.';

            return redirect()->route('student.bookings', ['booking' => $existingBooking->id])
                ->with('selected_booking_id', $existingBooking->id)
                ->with('success', $message);
        }

        $booking = DB::transaction(function () use ($request, $property) {
            $depositAmount = $property->deposit_amount ?? $property->monthly_rent;
            $totalAmount = $property->monthly_rent + $depositAmount;

            $booking = PropertyBooking::create([
                'booking_reference' => 'PB-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
                'student_id' => Auth::id(),
                'property_id' => $property->id,
                'landlord_id' => $property->landlord_id,
                'status' => PropertyBooking::STATUS_PENDING_LANDLORD_REVIEW,
                'move_in_date' => $request->move_in_date,
                'lease_months' => $request->lease_months,
                'occupants' => $request->occupants,
                'special_requests' => $request->special_requests,
                'quoted_rent' => $property->monthly_rent,
                'deposit_amount' => $depositAmount,
                'total_amount' => $totalAmount,
            ]);

            Payment::create([
                'student_id' => Auth::id(),
                'payable_type' => PropertyBooking::class,
                'payable_id' => $booking->id,
                'amount' => $totalAmount,
                'type' => 'rent',
                'status' => 'pending',
                'payment_method' => 'online',
                'payment_details' => [
                    'property_id' => $property->id,
                    'booking_reference' => $booking->booking_reference,
                    'rent_component' => $property->monthly_rent,
                    'deposit_component' => $depositAmount,
                ],
            ]);

            SystemNotification::notifyUser(
                $property->landlord_id,
                'New booking request',
                Auth::user()->name . ' has requested to book ' . $property->title . '. Please review and approve or reject.',
                route('landlord.bookings'),
                'info',
                Auth::id()
            );

            return $booking;
        });

        return redirect()->route('student.bookings', ['booking' => $booking->id])
            ->with('selected_booking_id', $booking->id)
            ->with('success', 'Booking request submitted. The landlord will review your request before you proceed.');
    }

    public function uploadSignedLease(Request $request, PropertyBooking $booking)
    {
        abort_unless($booking->student_id === Auth::id(), 403);

        if (!$booking->isApprovedAwaitingLease()) {
            return back()->with('error', 'You can only sign the lease after the landlord has approved your booking request.');
        }

        if (!$booking->property || !$booking->property->hasLeaseAgreement()) {
            return back()->with('error', 'This property does not have a lease agreement available yet.');
        }

        $request->validate(['signature' => 'required|string']);

        $signatureData = $request->input('signature');

        if (!str_starts_with($signatureData, 'data:image/png;base64,')) {
            return back()->with('error', 'Invalid signature format. Please sign and try again.');
        }

        $imageData = base64_decode(str_replace('data:image/png;base64,', '', $signatureData));

        if (!$imageData) {
            return back()->with('error', 'Could not process the signature. Please try again.');
        }

        if ($booking->signed_lease_path && Storage::disk('public')->exists($booking->signed_lease_path)) {
            Storage::disk('public')->delete($booking->signed_lease_path);
        }

        $path = 'signed-leases/' . Auth::id() . '/' . $booking->id . '/signature-' . now()->format('Ymd-His') . '.png';
        Storage::disk('public')->put($path, $imageData);

        $booking->update([
            'signed_lease_path'          => $path,
            'signed_lease_original_name' => 'Signed lease — ' . now()->format('d M Y'),
            'signed_lease_submitted_at'  => now(),
        ]);

        $booking->markLeaseSubmitted();

        SystemNotification::notifyUser(
            $booking->landlord_id,
            'Signed lease submitted',
            Auth::user()->name . ' has signed the lease for booking ' . $booking->booking_reference . '. Please review and approve or decline.',
            route('landlord.bookings'),
            'info',
            Auth::id()
        );

        return back()->with('success', 'Lease signed successfully. Awaiting landlord approval.');
    }

    public function bookings(Request $request)
    {
        $query = PropertyBooking::where('student_id', Auth::id())
            ->with(['property', 'payment'])
            ->latest();

        $selectedBooking = null;
        $selectedBookingId = $request->integer('booking');

        if (!$selectedBookingId) {
            $selectedBookingId = (int) $request->session()->pull('selected_booking_id');
        }

        $hasBookingFilter = $selectedBookingId > 0;

        if ($hasBookingFilter) {
            $query->whereKey($selectedBookingId);
        }

        $bookings = $query->paginate(10)->withQueryString();
        $selectedBooking = $hasBookingFilter ? $bookings->first() : null;

        return view('student.bookings', compact('bookings', 'selectedBooking', 'hasBookingFilter'));
    }

    public function viewingRequests(Request $request)
    {
        $query = ViewingRequest::where('student_id', Auth::id())
            ->with(['property', 'landlord'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $viewingRequests = $query->paginate(10)->withQueryString();

        return view('student.viewing-requests', compact('viewingRequests'));
    }

    public function enquiries(Request $request)
    {
        $query = PropertyEnquiry::where('student_id', Auth::id())
            ->with(['property', 'landlord'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->paginate(10)->withQueryString();

        return view('student.enquiries', compact('enquiries'));
    }

    public function storePropertyEnquiry(Request $request, Property $property)
    {
        $this->ensureApprovedProperty($property);

        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        PropertyEnquiry::create([
            'reference' => 'ENQ-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'student_id' => Auth::id(),
            'landlord_id' => $property->landlord_id,
            'property_id' => $property->id,
            'status' => 'pending',
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        SystemNotification::notifyUser(
            $property->landlord_id,
            'New property enquiry',
            Auth::user()->name . ' sent an enquiry about ' . $property->title . '.',
            route('landlord.enquiries'),
            'info',
            Auth::id()
        );

        return redirect()->route('student.enquiries')
            ->with('success', 'Your enquiry has been sent to the landlord.');
    }

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

    public function processPayment(Request $request)
    {
        $validated = $this->validatePaymentSubmission($request);

        $payment = Payment::where('id', $validated['payment_id'])
            ->where('student_id', Auth::id())
            ->with('payable')
            ->firstOrFail();

        if ($payment->status !== 'pending') {
            return redirect()->route('student.payments')
                ->with('error', 'This payment has already been processed.');
        }

        if ($payment->payable instanceof PropertyBooking) {
            if (!$payment->payable->isApprovedAwaitingPayment()) {
                return redirect()->route('student.bookings', ['booking' => $payment->payable->id])
                    ->with('selected_booking_id', $payment->payable->id)
                    ->with('error', 'Payment is not available at this stage of your booking.');
            }

            if ($payment->payable->property->available_units < 1) {
                return redirect()->route('student.bookings', ['booking' => $payment->payable->id])
                    ->with('selected_booking_id', $payment->payable->id)
                    ->with('error', 'This property is no longer available. Please choose another listing.');
            }
        }

        $methodCapture = $this->buildPaymentMethodCapture($validated);

        try {
            DB::transaction(function () use ($payment, $validated, $methodCapture) {
                $paymentDetails = is_array($payment->payment_details) ? $payment->payment_details : [];
                $paymentDetails['method_capture'] = $methodCapture;

                $payment->update([
                    'payment_method' => $validated['payment_method'],
                    'payment_details' => $paymentDetails,
                ]);
                $payment->markAsCompleted('TXN_' . strtoupper(Str::random(10)));

                if ($payment->payable instanceof PropertyBooking) {
                    if (!$payment->payable->confirm()) {
                        throw new RuntimeException('property_unavailable');
                    }

                    SystemNotification::notifyUser(
                        $payment->payable->landlord_id,
                        'Booking payment received',
                        'Payment was completed for booking ' . $payment->payable->booking_reference . '.',
                        route('landlord.bookings'),
                        'success',
                        Auth::id()
                    );
                }
            });
        } catch (RuntimeException $exception) {
            if ($exception->getMessage() === 'property_unavailable') {
                return redirect()->route('student.bookings', ['booking' => $payment->payable->id])
                    ->with('selected_booking_id', $payment->payable->id)
                    ->with('error', 'This property is no longer available. Please choose another listing.');
            }

            throw $exception;
        }

        SystemNotification::notifyUser(
            Auth::id(),
            'Payment successful',
            'Your payment of ' . $payment->formatted_amount . ' was processed successfully.',
            route('student.payments'),
            'success',
            Auth::id()
        );

        return redirect()->route('student.payments')
            ->with('success', 'Payment processed successfully.');
    }

    public function supportDesk()
    {
        $supportRequests = SupportRequest::where('student_id', Auth::id())
            ->latest()
            ->paginate(10);

        $categories = [
            'immigration',
            'registration',
            'accommodation',
            'finance',
            'onboarding',
            'other',
        ];

        return view('student.support-requests', compact('supportRequests', 'categories'));
    }

    public function storeSupportRequest(Request $request)
    {
        $request->validate([
            'category' => 'required|in:immigration,registration,accommodation,finance,onboarding,other',
            'priority' => 'required|in:low,medium,high',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $assignee = User::where('role', 'welfare')->first();

        SupportRequest::create([
            'reference' => 'SUP-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6)),
            'student_id' => Auth::id(),
            'assigned_to' => $assignee?->id,
            'category' => $request->category,
            'priority' => $request->priority,
            'status' => 'open',
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        if ($assignee) {
            SystemNotification::notifyUser(
                $assignee->id,
                'New virtual help desk request',
                Auth::user()->name . ' submitted a new support request.',
                route('welfare.support'),
                'warning',
                Auth::id()
            );
        }

        return redirect()->route('student.support')
            ->with('success', 'Your request has been submitted to the virtual help desk.');
    }

    private function ensureApprovedProperty(Property $property, bool $requireAvailability = false): void
    {
        abort_unless($property->review_status === 'approved' && $property->is_approved, 404);

        if ($requireAvailability) {
            abort_unless($property->is_available && $property->available_units > 0, 404);
        }
    }

    private function validatePaymentSubmission(Request $request): array
    {
        $currentYear = (int) now()->format('Y');

        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'payment_method' => 'required|in:card,bank_transfer,mobile_money',
            'cardholder_name' => 'nullable|required_if:payment_method,card|string|max:255',
            'card_number' => ['nullable', 'required_if:payment_method,card', 'string', 'max:23', 'regex:/^[0-9 ]{12,23}$/'],
            'expiry_month' => 'nullable|required_if:payment_method,card|integer|between:1,12',
            'expiry_year' => 'nullable|required_if:payment_method,card|integer|min:' . $currentYear . '|max:' . ($currentYear + 20),
            'cvv' => ['nullable', 'required_if:payment_method,card', 'string', 'regex:/^\d{3,4}$/'],
            'bank_name' => 'nullable|required_if:payment_method,bank_transfer|string|max:255',
            'account_name' => 'nullable|required_if:payment_method,bank_transfer|string|max:255',
            'account_number' => ['nullable', 'required_if:payment_method,bank_transfer', 'string', 'max:34', 'regex:/^[A-Za-z0-9 -]{6,34}$/'],
            'branch_code' => 'nullable|required_if:payment_method,bank_transfer|string|max:50',
            'mobile_provider' => 'nullable|required_if:payment_method,mobile_money|string|max:100',
            'mobile_number' => ['nullable', 'required_if:payment_method,mobile_money', 'string', 'max:20', 'regex:/^[0-9+\s-]{7,20}$/'],
        ]);

        if (($validated['payment_method'] ?? null) === 'card') {
            $expiryMonth = (int) $validated['expiry_month'];
            $expiryYear = (int) $validated['expiry_year'];
            $currentMonth = (int) now()->format('n');

            if ($expiryYear === $currentYear && $expiryMonth < $currentMonth) {
                throw ValidationException::withMessages([
                    'expiry_month' => 'The card expiry date must be in the future.',
                ]);
            }
        }

        return $validated;
    }

    private function buildPaymentMethodCapture(array $validated): array
    {
        return match ($validated['payment_method']) {
            'card' => [
                'kind' => 'card',
                'cardholder_name' => trim($validated['cardholder_name']),
                'card_last_four' => substr($this->digitsOnly($validated['card_number']), -4),
                'card_expiry' => sprintf('%02d/%d', (int) $validated['expiry_month'], (int) $validated['expiry_year']),
                'captured_at' => now()->toDateTimeString(),
            ],
            'bank_transfer' => [
                'kind' => 'bank_transfer',
                'bank_name' => trim($validated['bank_name']),
                'account_name' => trim($validated['account_name']),
                'account_number_masked' => $this->maskValue($validated['account_number']),
                'branch_code' => trim($validated['branch_code']),
                'captured_at' => now()->toDateTimeString(),
            ],
            'mobile_money' => [
                'kind' => 'mobile_money',
                'provider' => trim($validated['mobile_provider']),
                'mobile_number_masked' => $this->maskValue($validated['mobile_number']),
                'captured_at' => now()->toDateTimeString(),
            ],
        };
    }

    private function digitsOnly(string $value): string
    {
        return preg_replace('/\D+/', '', $value) ?? '';
    }

    private function maskValue(string $value, int $visibleCharacters = 4): string
    {
        $normalized = preg_replace('/\s+/', '', trim($value)) ?? '';
        $length = strlen($normalized);

        if ($length <= $visibleCharacters) {
            return $normalized;
        }

        return str_repeat('*', $length - $visibleCharacters) . substr($normalized, -$visibleCharacters);
    }
}
