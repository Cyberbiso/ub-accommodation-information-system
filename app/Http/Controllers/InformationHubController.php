<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampusOffice;
use App\Models\ImmigrationRequirement;
use App\Models\OnboardingChecklist;
use App\Models\Resource;

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

        $resources = $query->latest()->paginate(12);
        $categories = Resource::select('category')->distinct()->pluck('category');
        $types = Resource::select('type')->distinct()->pluck('type');

        return view('information.resources', compact('resources', 'categories', 'types'));
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