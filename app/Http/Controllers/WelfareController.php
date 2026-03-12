<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Accommodation;
use App\Models\User;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'available_rooms' => Accommodation::where('is_available', true)
                ->whereColumn('current_occupancy', '<', 'capacity')
                ->count(),
            'total_capacity' => Accommodation::sum('capacity'),
            'total_occupied' => Accommodation::sum('current_occupancy'),
            'occupancy_rate' => Accommodation::sum('capacity') > 0 
                ? round((Accommodation::sum('current_occupancy') / Accommodation::sum('capacity')) * 100, 2) 
                : 0,
            'total_students' => User::where('role', 'student')->count(),
            'housed_students' => Application::where('status', 'approved')->count(),
            'pending_documents' => StudentDocument::where('status', 'pending')->count(),
        ];

        $recentApplications = Application::with(['student', 'accommodation'])
            ->latest()
            ->take(5)
            ->get();

        $accommodations = Accommodation::select('name', 'current_occupancy', 'capacity', 'block')
            ->orderBy('block')
            ->get();
        
        $occupancyData = $accommodations->groupBy('block');

        return view('welfare.dashboard', compact('stats', 'recentApplications', 'occupancyData'));
    }

    public function applications(Request $request)
    {
        $query = Application::with(['student', 'accommodation']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
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
            ->get();
        
        return view('welfare.application-show', compact('application', 'availableAccommodations'));
    }

    public function approveApplication(Request $request, Application $application)
    {
        $request->validate([
            'accommodation_id' => 'required|exists:accommodations,id',
        ]);

        DB::beginTransaction();

        try {
            $application->update([
                'status' => 'approved',
                'processed_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            $accommodation = Accommodation::find($request->accommodation_id);
            $accommodation->increment('current_occupancy');

            DB::commit();

            return redirect()->route('welfare.applications')
                ->with('success', 'Application approved and room allocated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving application: ' . $e->getMessage());
        }
    }

    public function rejectApplication(Request $request, Application $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        $application->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

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
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:single,shared,family',
            'capacity'     => 'required|integer|min:1',
            'monthly_rent' => 'required|numeric|min:0',
            'block'        => 'nullable|string|max:10',
            'floor'        => 'nullable|integer|min:0',
        ]);

        $accommodation->update([
            'name'         => $request->name,
            'type'         => $request->type,
            'capacity'     => $request->capacity,
            'monthly_rent' => $request->monthly_rent,
            'block'        => $request->block,
            'floor'        => $request->floor,
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('welfare.accommodations')
            ->with('success', 'Accommodation updated successfully.');
    }

    /**
     * Display occupancy overview
     */
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
            'available_rooms' => Accommodation::where('is_available', true)
                ->whereColumn('current_occupancy', '<', 'capacity')
                ->count(),
        ];
        
        return view('welfare.occupancy', compact('accommodations', 'occupancyData', 'stats'));
    }

    /**
     * Display pending document verifications
     */
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

    /**
     * Show verify document form
     */
    public function verifyDocumentForm(StudentDocument $document)
    {
        return view('welfare.verify-document', compact('document'));
    }

    /**
     * Process document verification
     */
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
        
        // Update user's overall document status
        $user = $document->user;
        $allDocuments = $user->documents;
        
        if ($allDocuments->where('status', 'pending')->count() == 0) {
            if ($allDocuments->where('status', 'rejected')->count() > 0) {
                $user->document_status = 'rejected';
            } else {
                $user->document_status = 'verified';
                $user->documents_verified_at = now();
                $user->verified_by = auth()->id();
            }
            $user->save();
        }
        
        $message = $request->status == 'verified' 
            ? 'Document verified successfully.' 
            : 'Document rejected.';
        
        return redirect()->route('welfare.documents.pending')
            ->with('success', $message);
    }

    /**
     * Display accommodations filtered by block
     */
    public function accommodationsByBlock($block)
    {
        $accommodations = Accommodation::where('block', $block)
            ->orderBy('floor')
            ->orderBy('name')
            ->paginate(15);
        
        return view('welfare.accommodations', compact('accommodations', 'block'));
    }
}