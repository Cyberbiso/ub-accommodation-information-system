<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\ViewingRequest;
use Illuminate\Support\Facades\Auth;

class LandlordController extends Controller
{
    /**
     * Display landlord dashboard
     */
    public function dashboard()
    {
        $landlord = Auth::user();
        
        $stats = [
            'total_properties' => Property::where('landlord_id', $landlord->id)->count(),
            'active_properties' => Property::where('landlord_id', $landlord->id)
                ->where('is_available', true)
                ->count(),
            'pending_approvals' => Property::where('landlord_id', $landlord->id)
                ->where('is_approved', false)
                ->count(),
            'total_viewing_requests' => ViewingRequest::where('landlord_id', $landlord->id)->count(),
            'pending_viewings' => ViewingRequest::where('landlord_id', $landlord->id)
                ->where('status', 'pending')
                ->count(),
            'approved_viewings' => ViewingRequest::where('landlord_id', $landlord->id)
                ->where('status', 'approved')
                ->count(),
            'completed_viewings' => ViewingRequest::where('landlord_id', $landlord->id)
                ->where('status', 'completed')
                ->count(),
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

        return view('landlord.dashboard', compact('stats', 'recent_properties', 'recent_requests'));
    }

    /**
     * Display landlord properties
     */
    public function properties()
    {
        $properties = Property::where('landlord_id', Auth::id())
            ->latest()
            ->paginate(10);
        
        return view('landlord.properties', compact('properties'));
    }

    /**
     * Show create property form
     */
    public function createProperty()
    {
        return view('landlord.property-create');
    }

    /**
     * Store new property
     */
    public function storeProperty(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'monthly_rent' => 'required|numeric|min:0',
            'type' => 'required|in:apartment,house,shared,studio',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'distance_to_campus_km' => 'nullable|numeric|min:0',
        ]);

        Property::create([
            'landlord_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'address' => $request->address,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'monthly_rent' => $request->monthly_rent,
            'type' => $request->type,
            'bedrooms' => $request->bedrooms,
            'bathrooms' => $request->bathrooms,
            'distance_to_campus_km' => $request->distance_to_campus_km,
            'amenities' => $request->amenities ? json_encode($request->amenities) : null,
            'is_approved' => false,
        ]);

        return redirect()->route('landlord.properties')
            ->with('success', 'Property listed successfully! It will be visible after admin approval.');
    }

    /**
     * Display viewing requests
     */
    public function viewingRequests()
    {
        $requests = ViewingRequest::where('landlord_id', Auth::id())
            ->with(['student', 'property'])
            ->latest()
            ->paginate(10);
        
        return view('landlord.viewing-requests', compact('requests'));
    }

    /**
     * Approve viewing request
     */
    public function approveRequest(Request $request, ViewingRequest $viewingRequest)
    {
        $request->validate([
            'scheduled_date' => 'required|date|after:today',
        ]);

        $viewingRequest->update([
            'status' => 'approved',
            'scheduled_date' => $request->scheduled_date,
            'landlord_response' => $request->message,
        ]);

        return back()->with('success', 'Viewing request approved and scheduled.');
    }

    /**
     * Reject viewing request
     */
    public function rejectRequest(Request $request, ViewingRequest $viewingRequest)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $viewingRequest->update([
            'status' => 'rejected',
            'landlord_response' => $request->reason,
        ]);

        return back()->with('success', 'Viewing request rejected.');
    }
}