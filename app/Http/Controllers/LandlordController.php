<?php

namespace App\Http\Controllers;

use App\Models\LandlordVerificationDocument;
use App\Models\Property;
use App\Models\PropertyBooking;
use App\Models\PropertyEnquiry;
use App\Models\SystemNotification;
use App\Models\User;
use App\Models\ViewingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LandlordController extends Controller
{
    public function dashboard()
    {
        $landlord = Auth::user();

        $stats = [
            'total_properties' => Property::where('landlord_id', $landlord->id)->count(),
            'active_properties' => Property::where('landlord_id', $landlord->id)->where('is_available', true)->count(),
            'available_units' => Property::where('landlord_id', $landlord->id)->sum('available_units'),
            'pending_reviews' => Property::where('landlord_id', $landlord->id)->whereIn('review_status', ['pending', 'changes_requested'])->count(),
            'total_viewing_requests' => ViewingRequest::where('landlord_id', $landlord->id)->count(),
            'pending_viewings' => ViewingRequest::where('landlord_id', $landlord->id)->where('status', 'pending')->count(),
            'confirmed_bookings' => PropertyBooking::where('landlord_id', $landlord->id)->where('status', 'confirmed')->count(),
            'pending_enquiries' => PropertyEnquiry::where('landlord_id', $landlord->id)->where('status', 'pending')->count(),
        ];

        $recent_properties = Property::where('landlord_id', $landlord->id)
            ->latest()
            ->take(5)
            ->get();

        $recent_requests = ViewingRequest::where('landlord_id', $landlord->id)
            ->with(['student', 'property'])
            ->latest()
            ->take(5)
            ->get();

        $recent_bookings = PropertyBooking::where('landlord_id', $landlord->id)
            ->with(['student', 'property'])
            ->latest()
            ->take(5)
            ->get();

        $recent_enquiries = PropertyEnquiry::where('landlord_id', $landlord->id)
            ->with(['student', 'property'])
            ->latest()
            ->take(5)
            ->get();

        return view('landlord.dashboard', compact('stats', 'recent_properties', 'recent_requests', 'recent_bookings', 'recent_enquiries'));
    }

    public function verification()
    {
        $landlord = Auth::user();
        $documents = $landlord->landlordVerificationDocuments()->get()->keyBy('document_type');
        $steps = $landlord->landlordVerificationSteps();

        return view('landlord.verification', compact('landlord', 'documents', 'steps'));
    }

    public function updateVerification(Request $request)
    {
        $landlord = Auth::user();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'company_registration_number' => 'required|string|max:100',
            'tax_identification_number' => 'required|string|max:100',
            'company_registration_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'tax_clearance_certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'identity_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'property_ownership_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        $requiredDocuments = [
            'company_registration' => 'company_registration_document',
            'tax_clearance' => 'tax_clearance_certificate',
            'identity_document' => 'identity_document',
            'property_ownership' => 'property_ownership_document',
        ];

        foreach ($requiredDocuments as $documentType => $field) {
            $existing = $landlord->landlordVerificationDocuments()->where('document_type', $documentType)->exists();
            if (!$existing && !$request->hasFile($field)) {
                return back()->withErrors([$field => 'This verification document is required.'])->withInput();
            }
        }

        $verificationChanged = false;

        foreach ($requiredDocuments as $documentType => $field) {
            $statusReset = false;

            if ($request->hasFile($field)) {
                $this->storeLandlordVerificationDocument($landlord->id, $documentType, $request->file($field));
                $statusReset = true;
            } else {
                $documentQuery = $landlord->landlordVerificationDocuments()
                    ->where('document_type', $documentType)
                    ->latest();

                $existingDocument = $documentQuery->first();

                if ($existingDocument && $existingDocument->status !== 'verified') {
                    $documentQuery->update([
                        'status' => 'pending',
                        'review_notes' => null,
                        'verified_by' => null,
                        'verified_at' => null,
                    ]);
                    $statusReset = true;
                }
            }

            if ($statusReset) {
                $verificationChanged = true;
            }
        }

        $nextStage = $this->determineNextVerificationStage($landlord);

        $profilePayload = [
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'company_registration_number' => $request->company_registration_number,
            'tax_identification_number' => $request->tax_identification_number,
            'verification_notes' => null,
        ];

        if ($nextStage === 'completed' && $landlord->isVerifiedLandlord() && empty($verificationChanged)) {
            $landlord->update($profilePayload + [
                'landlord_verification_stage' => 'completed',
            ]);

            return redirect()->route('landlord.verification')
                ->with('success', 'Profile updated successfully. Your landlord verification remains approved.');
        }

        $landlord->update($profilePayload + [
            'landlord_verification_status' => 'pending',
            'landlord_verification_stage' => $nextStage,
            'landlord_verification_submitted_at' => now(),
            'landlord_verified_at' => null,
            'landlord_verification_reviewed_by' => null,
            'landlord_verification_reviewed_at' => null,
        ]);

        return redirect()->route('landlord.verification')
            ->with('success', 'Verification details submitted successfully. We will review your documents in stages before enabling property listings.');
    }

    public function properties()
    {
        $properties = Property::where('landlord_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('landlord.properties', compact('properties'));
    }

    public function createProperty()
    {
        if (!Auth::user()->isVerifiedLandlord()) {
            return redirect()->route('landlord.verification')
                ->with('error', 'Complete the multi-stage verification process before listing accommodation.');
        }

        return view('landlord.property-create', ['campus' => config('campus')]);
    }

    public function storeProperty(Request $request)
    {
        if (!Auth::user()->isVerifiedLandlord()) {
            return redirect()->route('landlord.verification')
                ->with('error', 'Complete verification before listing accommodation.');
        }

        $validated = $this->validateProperty($request);
        $property = Property::create($this->buildPropertyPayload($validated, null, $request));
        $this->notifyAdminsAboutPropertySubmission($property);

        return redirect()->route('landlord.properties')
            ->with('success', 'Property submitted successfully and is now awaiting admin approval.');
    }

    public function editProperty(Property $property)
    {
        $this->authorizeProperty($property);

        return view('landlord.property-edit', [
            'property' => $property,
            'campus' => config('campus'),
        ]);
    }

    public function updateProperty(Request $request, Property $property)
    {
        $this->authorizeProperty($property);

        $validated = $this->validateProperty($request);
        $property->update($this->buildPropertyPayload($validated, $property, $request));
        $this->notifyAdminsAboutPropertySubmission($property);

        return redirect()->route('landlord.properties')
            ->with('success', 'Property updated and submitted for admin review.');
    }

    public function destroyProperty(Property $property)
    {
        $this->authorizeProperty($property);
        $property->delete();

        return redirect()->route('landlord.properties')
            ->with('success', 'Property removed successfully.');
    }

    public function viewingRequests(Request $request)
    {
        $query = ViewingRequest::where('landlord_id', Auth::id())
            ->with(['student', 'property'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(10)->withQueryString();

        return view('landlord.viewing-requests', compact('requests'));
    }

    public function approveRequest(Request $request, ViewingRequest $viewingRequest)
    {
        abort_unless($viewingRequest->landlord_id === Auth::id(), 403);

        if ($viewingRequest->status !== 'pending') {
            return back()->with('error', 'Only pending viewing requests can be approved.');
        }

        $request->validate([
            'scheduled_date' => 'required|date|after:now',
            'message' => 'nullable|string|max:500',
        ]);

        $viewingRequest->update([
            'status' => 'approved',
            'scheduled_date' => $request->scheduled_date,
            'landlord_response' => $request->message,
        ]);

        SystemNotification::notifyUser(
            $viewingRequest->student_id,
            'Viewing request approved',
            'Your viewing request for ' . $viewingRequest->property->title . ' was approved.',
            route('student.viewing-requests'),
            'success',
            Auth::id()
        );

        return back()->with('success', 'Viewing request approved and scheduled.');
    }

    public function rejectRequest(Request $request, ViewingRequest $viewingRequest)
    {
        abort_unless($viewingRequest->landlord_id === Auth::id(), 403);

        if ($viewingRequest->status !== 'pending') {
            return back()->with('error', 'Only pending viewing requests can be rejected.');
        }

        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $viewingRequest->update([
            'status' => 'rejected',
            'landlord_response' => $request->reason,
        ]);

        SystemNotification::notifyUser(
            $viewingRequest->student_id,
            'Viewing request rejected',
            'Your viewing request for ' . $viewingRequest->property->title . ' was declined.',
            route('student.viewing-requests'),
            'warning',
            Auth::id()
        );

        return back()->with('success', 'Viewing request rejected.');
    }

    public function bookings()
    {
        $bookings = PropertyBooking::where('landlord_id', Auth::id())
            ->with(['property', 'student', 'payment'])
            ->latest()
            ->paginate(10);

        return view('landlord.bookings', compact('bookings'));
    }

    public function enquiries(Request $request)
    {
        $query = PropertyEnquiry::where('landlord_id', Auth::id())
            ->with(['student', 'property'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enquiries = $query->paginate(10)->withQueryString();

        return view('landlord.enquiries', compact('enquiries'));
    }

    public function respondToEnquiry(Request $request, PropertyEnquiry $enquiry)
    {
        abort_unless($enquiry->landlord_id === Auth::id(), 403);

        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        $enquiry->update([
            'response' => $validated['response'],
            'status' => 'responded',
            'responded_at' => now(),
        ]);

        SystemNotification::notifyUser(
            $enquiry->student_id,
            'Property enquiry responded to',
            'The landlord responded to your enquiry about ' . $enquiry->property->title . '.',
            route('student.enquiries'),
            'info',
            Auth::id()
        );

        return back()->with('success', 'Response sent to student.');
    }

    private function authorizeProperty(Property $property): void
    {
        abort_unless($property->landlord_id === Auth::id(), 403);
    }

    private function validateProperty(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:50',
            'monthly_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'type' => 'required|in:apartment,house,shared,studio',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'available_units' => 'required|integer|min:1',
            'distance_to_campus_km' => 'nullable|numeric|min:0',
            'google_maps_location' => 'nullable|string|max:2000',
            'amenities_input' => 'nullable|string|max:2000',
            'transport_routes_input' => 'nullable|string|max:2000',
            'nearby_amenities_input' => 'nullable|string|max:2000',
            'navigation_notes' => 'nullable|string|max:2000',
            'photos' => 'nullable|array|max:6',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
    }

    private function buildPropertyPayload(array $validated, ?Property $property = null, ?Request $request = null): array
    {
        [$latitude, $longitude] = $this->resolvePropertyCoordinates($validated, $property);
        $distance = $validated['distance_to_campus_km'] ?? null;

        if ($latitude !== null && $longitude !== null) {
            $distance = $this->calculateDistanceToCampus($latitude, $longitude);
        }

        $storedPhotos = $property?->photos ?? [];
        if ($request && $request->hasFile('photos')) {
            $storedPhotos = array_merge($storedPhotos, $this->storePropertyPhotos($request));
        }

        return [
            'landlord_id' => $property?->landlord_id ?? Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'postal_code' => $validated['postal_code'] ?? null,
            'monthly_rent' => $validated['monthly_rent'],
            'deposit_amount' => $validated['deposit_amount'] ?? 0,
            'type' => $validated['type'],
            'bedrooms' => $validated['bedrooms'],
            'bathrooms' => $validated['bathrooms'],
            'available_units' => $validated['available_units'],
            'distance_to_campus_km' => $distance,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'amenities' => $this->parseList($validated['amenities_input'] ?? null),
            'transport_routes' => $this->parseList($validated['transport_routes_input'] ?? null),
            'nearby_amenities' => $this->parseList($validated['nearby_amenities_input'] ?? null),
            'navigation_notes' => $validated['navigation_notes'] ?? null,
            'photos' => $storedPhotos,
            'is_approved' => false,
            'is_available' => $validated['available_units'] > 0,
            'review_status' => 'pending',
            'review_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'listed_at' => $property?->listed_at,
        ];
    }

    private function parseList(?string $value): array
    {
        if (!$value) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function resolvePropertyCoordinates(array $validated, ?Property $property = null): array
    {
        $latitude = $property?->latitude;
        $longitude = $property?->longitude;
        $locationInput = trim((string) ($validated['google_maps_location'] ?? ''));

        if ($locationInput === '') {
            return [$latitude, $longitude];
        }

        [$resolvedLatitude, $resolvedLongitude] = $this->extractCoordinatesFromLocationInput($locationInput);

        if ($resolvedLatitude === null || $resolvedLongitude === null) {
            throw ValidationException::withMessages([
                'google_maps_location' => 'Paste a full Google Maps pin URL or coordinates like "-24.6282, 25.9231". Short links cannot be read automatically.',
            ]);
        }

        return [$resolvedLatitude, $resolvedLongitude];
    }

    private function extractCoordinatesFromLocationInput(string $value): array
    {
        $value = trim(urldecode(html_entity_decode($value)));
        $patterns = [
            '/@(-?\d{1,3}(?:\.\d+)?),\s*(-?\d{1,3}(?:\.\d+)?)/',
            '/[?&](?:q|query|destination|center|ll)=(-?\d{1,3}(?:\.\d+)?),\s*(-?\d{1,3}(?:\.\d+)?)/',
            '/!3d(-?\d{1,3}(?:\.\d+)?)!4d(-?\d{1,3}(?:\.\d+)?)/',
            '/(-?\d{1,3}(?:\.\d+)?)\s*,\s*(-?\d{1,3}(?:\.\d+)?)/',
        ];

        foreach ($patterns as $pattern) {
            if (!preg_match($pattern, $value, $matches)) {
                continue;
            }

            $latitude = (float) $matches[1];
            $longitude = (float) $matches[2];

            if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
                continue;
            }

            return [$latitude, $longitude];
        }

        return [null, null];
    }

    private function calculateDistanceToCampus(float $latitude, float $longitude): float
    {
        $campus = config('campus');
        $earthRadius = 6371;
        $latFrom = deg2rad($campus['latitude']);
        $lonFrom = deg2rad($campus['longitude']);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return round($angle * $earthRadius, 2);
    }

    private function determineNextVerificationStage($landlord): string
    {
        foreach (array_keys($landlord->landlordVerificationSteps()) as $stage) {
            $document = $landlord->landlordVerificationDocuments()
                ->where('document_type', $stage)
                ->latest()
                ->first();

            if (!$document || $document->status !== 'verified') {
                return $stage;
            }
        }

        return 'completed';
    }

    private function storePropertyPhotos(Request $request): array
    {
        return collect($request->file('photos', []))
            ->map(function ($photo) {
                return $photo->store('property-photos/' . Auth::id(), 'public');
            })
            ->all();
    }

    private function notifyAdminsAboutPropertySubmission(Property $property): void
    {
        User::where('role', 'admin')
            ->pluck('id')
            ->each(function (int $adminId) use ($property) {
                SystemNotification::notifyUser(
                    $adminId,
                    'Property listing pending approval',
                    $property->title . ' was submitted for admin review.',
                    route('admin.properties.pending'),
                    'warning',
                    Auth::id()
                );
            });
    }

    private function storeLandlordVerificationDocument(int $userId, string $documentType, $file): void
    {
        $path = $file->store('landlord-documents/' . $userId, 'public');

        $existing = LandlordVerificationDocument::where('user_id', $userId)
            ->where('document_type', $documentType)
            ->latest()
            ->first();

        if ($existing) {
            if ($existing->path && Storage::disk('public')->exists($existing->path)) {
                Storage::disk('public')->delete($existing->path);
            }

            $existing->update([
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'status' => 'pending',
                'review_notes' => null,
                'verified_by' => null,
                'verified_at' => null,
            ]);

            return;
        }

        LandlordVerificationDocument::create([
            'user_id' => $userId,
            'document_type' => $documentType,
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'status' => 'pending',
        ]);
    }
}
