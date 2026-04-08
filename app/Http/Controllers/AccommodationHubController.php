<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accommodation;
use App\Models\Property;
use App\Models\ViewingRequest;

class AccommodationHubController extends Controller
{
    /**
     * Display the accommodation hub homepage
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'on_campus' => Accommodation::where('is_available', true)->count() ?: 8,
            'off_campus' => Property::live()->count() ?: 5,
            'landlords' => \App\Models\User::where('role', 'landlord')->where('landlord_verification_status', 'verified')->count() ?: 3,
            'viewings' => ViewingRequest::count() ?: 12,
        ];

        // Get featured accommodations
        $featuredAccommodations = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity')
            ->inRandomOrder()
            ->take(3)
            ->get();

        // Get featured properties
        $featuredProperties = Property::live()
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('accommodation-hub', compact('stats', 'featuredAccommodations', 'featuredProperties'));
    }

    /**
     * Display all on-campus accommodations
     */
    public function onCampus(Request $request)
    {
        $query = Accommodation::where('is_available', true)
            ->whereColumn('current_occupancy', '<', 'capacity');

        // Apply filters
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

        return view('accommodation.on-campus', compact('accommodations', 'types', 'blocks'));
    }

    /**
     * Display all off-campus properties
     */
    public function offCampus(Request $request)
    {
        $query = Property::live();

        // Apply filters
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('max_price')) {
            $query->where('monthly_rent', '<=', $request->max_price);
        }
        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Sorting
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
                default:
                    $query->latest();
            }
        }

        $cities = Property::approved()->select('city')->distinct()->pluck('city');
        $types = Property::approved()->select('type')->distinct()->pluck('type');
        
        $properties = $query->paginate(12);

        return view('accommodation.off-campus', compact('properties', 'cities', 'types'));
    }

    /**
     * Show single accommodation
     */
    public function showAccommodation(Accommodation $accommodation)
    {
        return view('accommodation.show', compact('accommodation'));
    }

    /**
     * Show single property details
     */
    public function showProperty(Property $property)
    {
        // Check if property is approved and available
        if ($property->review_status !== 'approved' || !$property->is_approved || !$property->is_available) {
            abort(404, 'Property not found or not available.');
        }
        
        return view('accommodation.property-show', compact('property'));
    }
}
