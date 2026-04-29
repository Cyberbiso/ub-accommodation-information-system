<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Application;
use App\Models\LandlordVerificationDocument;
use App\Models\StudentDocument;
use App\Models\SupportRequest;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WelfareController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'waitlisted_applications' => Application::where('status', 'waitlisted')->count(),
            'total_accommodations' => Accommodation::count(),
            'available_rooms' => Accommodation::where('is_available', true)->whereColumn('current_occupancy', '<', 'capacity')->count(),
            'total_capacity' => Accommodation::sum('capacity'),
            'total_occupied' => Accommodation::sum('current_occupancy'),
            'occupancy_rate' => Accommodation::sum('capacity') > 0
                ? round((Accommodation::sum('current_occupancy') / Accommodation::sum('capacity')) * 100, 2)
                : 0,
            'pending_documents' => StudentDocument::where('status', 'pending')->count(),
            'pending_landlord_verifications' => User::where('role', 'landlord')->where('landlord_verification_status', 'pending')->count(),
            'open_support_requests' => SupportRequest::whereIn('status', ['open', 'in_progress'])->count(),
        ];

        $recentApplications = Application::with(['student', 'accommodation'])->latest()->take(5)->get();
        $pendingLandlords = User::where('role', 'landlord')
            ->where('landlord_verification_status', 'pending')
            ->latest()
            ->take(5)
            ->get();
        $supportQueue = SupportRequest::with('student')
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->take(5)
            ->get();

        $accommodations = Accommodation::select('name', 'current_occupancy', 'capacity', 'block')
            ->orderBy('block')
            ->get();

        $occupancyData = $accommodations->groupBy('block');

        return view('welfare.dashboard', compact(
            'stats',
            'recentApplications',
            'occupancyData',
            'pendingLandlords',
            'supportQueue'
        ));
    }

    public function applications(Request $request)
    {
        $query = Application::with(['student', 'accommodation']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($studentQuery) use ($search) {
                $studentQuery->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('student_id', 'LIKE', '%' . $search . '%');
            });
        }

        $applications = $query->latest()->paginate(15);

        return view('welfare.applications', compact('applications'));
    }

    public function showApplication(Application $application)
    {
        $application->load(['student', 'accommodation', 'student.documents']);
        $availableAccommodations = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->orderBy('block')
            ->orderBy('name')
            ->get();

        return view('welfare.application-show', compact('application', 'availableAccommodations'));
    }

    public function approveApplication(Request $request, Application $application)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
        ]);

        if (!in_array($application->status, ['pending', 'waitlisted'], true)) {
            return back()->with('error', 'Only pending or waitlisted applications can be approved.');
        }

        $accommodation = Accommodation::findOrFail($request->accommodation_id);

        if (!$accommodation->hasSpace()) {
            return back()->with('error', 'The selected room is full.');
        }

        DB::transaction(function () use ($application, $accommodation) {
            $application->update([
                'accommodation_id' => $accommodation->id,
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            $accommodation->increment('current_occupancy');
            $accommodation->refresh();
            $accommodation->update([
                'is_available' => $accommodation->current_occupancy < $accommodation->capacity,
            ]);
        });

        SystemNotification::notifyUser(
            $application->student_id,
            'Accommodation application approved',
            'Your on-campus accommodation application was approved and a room has been allocated to you.',
            route('student.applications.show', $application),
            'success',
            Auth::id()
        );

        return redirect()->route('welfare.applications')
            ->with('success', 'Application approved and room allocated successfully.');
    }

    public function rejectApplication(Request $request, Application $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if (!in_array($application->status, ['pending', 'waitlisted'], true)) {
            return back()->with('error', 'Only pending or waitlisted applications can be rejected.');
        }

        $application->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
        ]);

        SystemNotification::notifyUser(
            $application->student_id,
            'Accommodation application rejected',
            'Your on-campus application was rejected. Review the latest notes for more detail.',
            route('student.applications.show', $application),
            'warning',
            Auth::id()
        );

        return redirect()->route('welfare.applications')
            ->with('success', 'Application rejected.');
    }

    public function accommodations()
    {
        $accommodations = Accommodation::orderBy('block')->orderBy('floor')->paginate(15);

        return view('welfare.accommodations', compact('accommodations'));
    }

    public function createAccommodation()
    {
        return view('welfare.accommodation-create');
    }

    public function storeAccommodation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,shared,family',
            'capacity' => 'required|integer|min:1',
            'monthly_rent' => 'required|numeric|min:0',
            'block' => 'nullable|string|max:10',
            'floor' => 'nullable|integer|min:0',
        ]);

        Accommodation::create([
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'current_occupancy' => 0,
            'monthly_rent' => $request->monthly_rent,
            'block' => $request->block,
            'floor' => $request->floor,
            'is_available' => true,
        ]);

        return redirect()->route('welfare.accommodations')
            ->with('success', 'Accommodation added successfully.');
    }

    public function editAccommodation(Accommodation $accommodation)
    {
        return view('welfare.accommodation-edit', compact('accommodation'));
    }

    public function updateAccommodation(Request $request, Accommodation $accommodation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:single,shared,family',
            'capacity' => 'required|integer|min:1',
            'monthly_rent' => 'required|numeric|min:0',
            'block' => 'nullable|string|max:10',
            'floor' => 'nullable|integer|min:0',
        ]);

        $accommodation->update([
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'monthly_rent' => $request->monthly_rent,
            'block' => $request->block,
            'floor' => $request->floor,
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('welfare.accommodations')
            ->with('success', 'Accommodation updated successfully.');
    }

    public function occupancyOverview()
    {
        $accommodations = Accommodation::select('name', 'current_occupancy', 'capacity', 'block', 'floor', 'type')
            ->orderBy('block')
            ->orderBy('floor')
            ->get();

        $occupancyData = $accommodations->groupBy('block');
        $stats = [
            'total_capacity' => Accommodation::sum('capacity'),
            'total_occupied' => Accommodation::sum('current_occupancy'),
            'occupancy_rate' => Accommodation::sum('capacity') > 0
                ? round((Accommodation::sum('current_occupancy') / Accommodation::sum('capacity')) * 100, 2)
                : 0,
            'available_rooms' => Accommodation::where('is_available', true)->whereColumn('current_occupancy', '<', 'capacity')->count(),
        ];

        return view('welfare.occupancy', compact('accommodations', 'occupancyData', 'stats'));
    }

    public function pendingDocuments()
    {
        $pendingDocuments = StudentDocument::with(['user'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(15);

        $stats = [
            'total_pending' => StudentDocument::where('status', 'pending')->count(),
            'total_verified' => StudentDocument::where('status', 'verified')->count(),
            'total_rejected' => StudentDocument::where('status', 'rejected')->count(),
        ];

        return view('welfare.pending-documents', compact('pendingDocuments', 'stats'));
    }

    public function verifyDocumentForm(StudentDocument $document)
    {
        return view('welfare.verify-document', compact('document'));
    }

    public function verifyDocument(Request $request, StudentDocument $document)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ]);

        $document->update([
            'status' => $request->status,
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $user = $document->user;
        $allDocuments = $user->documents;

        if ($allDocuments->where('status', 'pending')->count() === 0) {
            if ($allDocuments->where('status', 'rejected')->count() > 0) {
                $user->document_status = 'rejected';
                $user->documents_verified_at = null;
                $user->verified_by = null;
            } else {
                $user->document_status = 'verified';
                $user->documents_verified_at = now();
                $user->verified_by = auth()->id();
            }
            $user->save();
        }

        $message = $request->status === 'verified'
            ? 'Document verified successfully.'
            : 'Document rejected.';

        SystemNotification::notifyUser(
            $user->id,
            $request->status === 'verified' ? 'Document verified' : 'Document rejected',
            $request->status === 'verified'
                ? $document->document_type_label . ' was verified successfully.'
                : $document->document_type_label . ' was rejected. Please review the notes and resubmit if required.',
            route('student.dashboard'),
            $request->status === 'verified' ? 'success' : 'warning',
            Auth::id()
        );

        return redirect()->route('welfare.documents.pending')
            ->with('success', $message);
    }

    public function landlordVerifications()
    {
        $landlords = User::where('role', 'landlord')
            ->with('landlordVerificationDocuments')
            ->latest()
            ->paginate(15);

        return view('welfare.landlord-verifications', compact('landlords'));
    }

    public function processLandlordVerification(Request $request, User $landlord)
    {
        abort_unless($landlord->isLandlord(), 404);

        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:2000',
        ]);

        $currentStage = $landlord->landlord_verification_stage ?: 'company_registration';
        $document = $landlord->landlordVerificationDocuments()
            ->where('document_type', $currentStage)
            ->latest()
            ->first();

        if (!$document) {
            return back()->with('error', 'The current verification document is missing.');
        }

        $document->update([
            'status' => $request->action === 'approve' ? 'verified' : 'rejected',
            'review_notes' => $request->notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        if ($request->action === 'reject') {
            $landlord->update([
                'landlord_verification_status' => 'rejected',
                'verification_notes' => $request->notes,
            ]);

            return back()->with('success', 'Landlord verification stage rejected.');
        }

        $stages = array_keys($landlord->landlordVerificationSteps());
        $currentIndex = array_search($currentStage, $stages, true);
        $nextStage = $stages[$currentIndex + 1] ?? null;

        if ($nextStage) {
            $landlord->update([
                'landlord_verification_status' => 'pending',
                'landlord_verification_stage' => $nextStage,
                'verification_notes' => $request->notes,
            ]);

            return back()->with('success', 'Stage approved. The landlord has advanced to the next verification stage.');
        }

        $landlord->update([
            'landlord_verification_status' => 'verified',
            'landlord_verification_stage' => 'completed',
            'landlord_verified_at' => now(),
            'verification_notes' => $request->notes,
        ]);

        return back()->with('success', 'Landlord fully verified and now eligible to advertise accommodation.');
    }

    public function reviewLandlordVerificationDocument(Request $request, LandlordVerificationDocument $document)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,request_more_info,reject',
            'notes' => 'nullable|string|max:2000',
        ]);

        $landlord = $document->user;
        abort_unless($landlord && $landlord->isLandlord(), 404);

        $document->update([
            'status' => match ($validated['action']) {
                'approve' => 'verified',
                'request_more_info' => 'more_info_required',
                default => 'rejected',
            },
            'review_notes' => $validated['notes'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        $landlord = $landlord->fresh('landlordVerificationDocuments');
        $stages = array_keys($landlord->landlordVerificationSteps());
        $latestByStage = [];
        foreach ($landlord->landlordVerificationDocuments as $doc) {
            $existing = $latestByStage[$doc->document_type] ?? null;
            if (!$existing || $doc->id > $existing->id) {
                $latestByStage[$doc->document_type] = $doc;
            }
        }

        $statuses = [];
        foreach ($stages as $stage) {
            $statuses[$stage] = $latestByStage[$stage]->status ?? 'pending';
        }

        $hasRejected = in_array('rejected', $statuses, true);
        $hasMoreInfo = in_array('more_info_required', $statuses, true);
        $allVerified = !empty($statuses) && count(array_filter($statuses, fn ($s) => $s === 'verified')) === count($statuses);

        if ($hasRejected) {
            $newStatus = 'rejected';
            $verifiedAt = null;
        } elseif ($allVerified) {
            $newStatus = 'verified';
            $verifiedAt = now();
        } elseif ($hasMoreInfo) {
            $newStatus = 'needs_more_info';
            $verifiedAt = null;
        } else {
            $newStatus = 'pending';
            $verifiedAt = null;
        }

        $nextStage = 'completed';
        foreach ($stages as $stage) {
            if (($statuses[$stage] ?? 'pending') !== 'verified') {
                $nextStage = $stage;
                break;
            }
        }

        $landlord->update([
            'landlord_verification_status' => $newStatus,
            'landlord_verification_stage' => $nextStage,
            'landlord_verified_at' => $verifiedAt,
            'landlord_verification_reviewed_by' => Auth::id(),
            'landlord_verification_reviewed_at' => now(),
        ]);

        $stageLabel = $document->document_type_label;
        $titles = [
            'approve' => $stageLabel . ' approved',
            'request_more_info' => $stageLabel . ' needs more information',
            'reject' => $stageLabel . ' rejected',
        ];
        $body = $validated['notes'] ? 'Notes: ' . $validated['notes'] : 'No reviewer notes provided.';
        SystemNotification::notifyUser(
            $landlord->id,
            $titles[$validated['action']],
            $body,
            route('landlord.verification'),
            'info',
            Auth::id()
        );

        return back()->with('success', $stageLabel . ' updated.');
    }

    public function supportRequests()
    {
        $supportRequests = SupportRequest::with(['student', 'assignee'])
            ->latest()
            ->paginate(15);

        return view('welfare.support-requests', compact('supportRequests'));
    }

    public function updateSupportRequest(Request $request, SupportRequest $supportRequest)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'resolution_notes' => 'nullable|string|max:5000',
        ]);

        $supportRequest->update([
            'assigned_to' => Auth::id(),
            'status' => $request->status,
            'resolution_notes' => $request->resolution_notes,
            'resolved_at' => in_array($request->status, ['resolved', 'closed'], true) ? now() : null,
        ]);

        SystemNotification::notifyUser(
            $supportRequest->student_id,
            'Support request updated',
            'Your support request ' . $supportRequest->reference . ' is now marked as ' . str_replace('_', ' ', $request->status) . '.',
            route('student.support'),
            in_array($request->status, ['resolved', 'closed'], true) ? 'success' : 'info',
            Auth::id()
        );

        return redirect()->route('welfare.support')
            ->with('success', 'Support request updated successfully.');
    }

    public function accommodationsByBlock($block)
    {
        $accommodations = Accommodation::where('block', $block)
            ->orderBy('floor')
            ->orderBy('name')
            ->paginate(15);

        return view('welfare.accommodations', compact('accommodations', 'block'));
    }
}
