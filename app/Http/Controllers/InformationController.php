<?php
namespace App\Http\Controllers;

use App\Models\CampusOffice;
use App\Models\ImmigrationRequirement;
use App\Models\OnboardingChecklist;
use App\Models\Resource;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    /**
     * Display the main information hub
     */
    public function index()
    {
        $featuredResources = Resource::where('is_featured', true)
            ->where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        $urgentDeadlines = ImmigrationRequirement::where('priority', 1)
            ->where('is_active', true)
            ->take(3)
            ->get();

        $campusOffices = CampusOffice::active()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('information.index', compact(
            'featuredResources', 
            'urgentDeadlines', 
            'campusOffices'
        ));
    }

    /**
     * Display campus directory
     */
    public function campusDirectory(Request $request)
    {
        $query = CampusOffice::active();

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('office_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('building', 'like', '%' . $request->search . '%');
            });
        }

        $offices = $query->orderBy('sort_order')->paginate(12);
        $categories = CampusOffice::select('category')->distinct()->pluck('category');

        return view('information.campus-directory', compact('offices', 'categories'));
    }

    /**
     * Display immigration requirements
     */
    public function immigrationCompliance(Request $request)
    {
        $query = ImmigrationRequirement::where('is_active', true);

        if ($request->filled('category')) {
            $query->ofCategory($request->category);
        }

        $requirements = $query->orderBy('priority')
            ->orderBy('deadline')
            ->paginate(10);

        $categories = ImmigrationRequirement::select('category')->distinct()->pluck('category');

        return view('information.immigration', compact('requirements', 'categories'));
    }

    /**
     * Display onboarding checklist
     */
    public function onboardingChecklist(Request $request)
    {
        $checklist = OnboardingChecklist::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('information.checklist', compact('checklist'));
    }

    /**
     * Display resources library
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
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $resources = $query->latest()->paginate(15);
        $categories = Resource::select('category')->distinct()->pluck('category');
        $types = Resource::select('type')->distinct()->pluck('type');

        return view('information.resources', compact('resources', 'categories', 'types'));
    }

    /**
     * Download resource
     */
    public function downloadResource(Resource $resource)
    {
        $resource->increment('download_count');

        if ($resource->type === 'document' && $resource->file_path) {
            return response()->download(storage_path('app/public/' . $resource->file_path));
        }

        return redirect($resource->external_link);
    }

    /**
     * Show single campus office
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