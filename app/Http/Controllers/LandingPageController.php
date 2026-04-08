<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\Property;

/**
 * Landing Page Controller
 * 
 * Handles the public-facing homepage of the UB Accommodation Information System.
 * Shows featured accommodations, properties, and system information.
 */
class LandingPageController extends Controller
{
    /**
     * Display the landing page
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        
    
    // Get statistics for the landing page
        $stats = [
            'on_campus_rooms' => Accommodation::where('is_available', true)->count(),
            'off_campus_properties' => Property::live()->count(),
            'active_landlords' => \App\Models\User::where('role', 'landlord')->where('landlord_verification_status', 'verified')->count(),
            'happy_students' => \App\Models\User::where('role', 'student')->count(),
        ];

        // Get some featured properties (random for demo)
        $featuredProperties = Property::live()
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Get some featured accommodations
        $featuredAccommodations = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('landing', compact('stats', 'featuredProperties', 'featuredAccommodations'));
    }

    /**
     * Display about us page
     * 
     * @return \Illuminate\View\View
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display contact page
     * 
     * @return \Illuminate\View\View
     */
    public function contact()
    {
        return view('contact');
    }

    /**
     * Display FAQ page
     * 
     * @return \Illuminate\View\View
     */
    public function faq()
    {
        return view('faq');
    }

    /**
     * Handle contact form submission
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Here you would typically send an email or save to database
        // For now, we'll just redirect with a success message

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
