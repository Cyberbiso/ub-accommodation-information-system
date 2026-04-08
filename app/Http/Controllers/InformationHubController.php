<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampusOffice;
use App\Models\ImmigrationRequirement;
use App\Models\OnboardingChecklist;
use App\Models\Resource;
use Illuminate\Support\Facades\Storage;

class InformationHubController extends Controller
{
    /**
     * Display the information hub homepage
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'offices' => CampusOffice::count() ?: 10,
            'requirements' => ImmigrationRequirement::count() ?: 8,
            'checklist' => OnboardingChecklist::count() ?: 15,
            'resources' => Resource::count() ?: 12,
        ];

        // Get featured offices
        $featuredOffices = CampusOffice::inRandomOrder()->take(3)->get();

        // Get urgent deadlines
        $urgentDeadlines = ImmigrationRequirement::where('priority', 1)
            ->where('is_active', true)
            ->take(3)
            ->get();

        // Get recent resources
        $recentResources = Resource::where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        return view('information-hub', compact('stats', 'featuredOffices', 'urgentDeadlines', 'recentResources'));
    }

    /**
     * Display campus directory
     */
    public function campusDirectory(Request $request)
    {
        $query = CampusOffice::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('office_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $offices = $query->orderBy('office_name')->paginate(12);
        $categories = CampusOffice::select('category')->distinct()->pluck('category');

        return view('information.campus-directory', compact('offices', 'categories'));
    }

    /**
     * Display immigration requirements
     */
    public function immigration(Request $request)
    {
        $query = ImmigrationRequirement::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $requirements = $query->orderBy('priority')->orderBy('deadline')->paginate(10);
        $categories = ImmigrationRequirement::select('category')->distinct()->pluck('category');

        return view('information.immigration', compact('requirements', 'categories'));
    }

    /**
     * Display onboarding checklist
     */
    public function checklist()
    {
        $checklist = OnboardingChecklist::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('information.checklist', compact('checklist'));
    }

    /**
     * Display resources
     */
    public function resources(Request $request)
    {
        $query = Resource::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($resourceQuery) use ($search) {
                $resourceQuery->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $resources = $query->latest()->paginate(12);
        $categories = Resource::where('is_active', true)->select('category')->distinct()->pluck('category');
        $types = Resource::where('is_active', true)->select('type')->distinct()->pluck('type');

        return view('information.resources', compact('resources', 'categories', 'types'));
    }

    public function downloadResource(Resource $resource)
    {
        abort_unless($resource->is_active, 404);

        $resource->increment('download_count');

        if ($resource->file_path && Storage::disk('public')->exists($resource->file_path)) {
            return response()->download(
                storage_path('app/public/' . $resource->file_path),
                basename($resource->file_path)
            );
        }

        if ($resource->external_link) {
            return redirect()->away($resource->external_link);
        }

        abort(404);
    }

    /**
     * Show single office
     */
    public function showOffice(CampusOffice $office)
    {
        return view('information.office-show', compact('office'));
    }

    /**
     * Show single requirement
     */
    public function showRequirement(ImmigrationRequirement $requirement)
    {
        return view('information.requirement-show', compact('requirement'));
    }
}
