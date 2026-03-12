<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Property;
use App\Models\Application;
use App\Models\Accommodation;
use App\Models\Payment;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_landlords' => User::where('role', 'landlord')->count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('is_approved', false)->count(),
            'approved_properties' => Property::where('is_approved', true)->count(),
            'total_applications' => Application::count(),
            'total_accommodations' => Accommodation::count(),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
        ];

        $recent_users = User::latest()->take(5)->get();
        $pending_properties = Property::where('is_approved', false)
            ->with('landlord')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'pending_properties'));
    }

    /**
     * Display all users
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(15);
        
        return view('admin.users', compact('users'));
    }

    /**
     * Display pending properties
     */
    public function pendingProperties()
    {
        $properties = Property::where('is_approved', false)
            ->with('landlord')
            ->latest()
            ->paginate(15);
        
        return view('admin.pending-properties', compact('properties'));
    }

    /**
     * Approve property
     */
    public function approveProperty(Property $property)
    {
        $property->update(['is_approved' => true]);
        
        return back()->with('success', 'Property approved successfully.');
    }

    /**
     * Reject property
     */
    public function rejectProperty(Request $request, Property $property)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        // You could add a reason field to properties table if needed
        $property->delete();

        return back()->with('success', 'Property rejected and removed.');
    }
}