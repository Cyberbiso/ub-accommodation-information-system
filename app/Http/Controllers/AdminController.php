<?php

namespace App\Http\Controllers;

use App\Models\Accommodation;
use App\Models\Announcement;
use App\Models\Application;
use App\Models\Payment;
use App\Models\Property;
use App\Models\SystemNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'total_students' => User::where('role', 'student')->count(),
            'total_landlords' => User::where('role', 'landlord')->count(),
            'pending_landlord_verifications' => User::where('role', 'landlord')
                ->whereIn('landlord_verification_status', ['pending', 'needs_more_info'])
                ->count(),
            'verified_landlords' => User::where('role', 'landlord')
                ->where('landlord_verification_status', 'verified')
                ->count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::whereIn('review_status', ['pending', 'changes_requested'])->count(),
            'approved_properties' => Property::where('review_status', 'approved')->count(),
            'removed_properties' => Property::where('review_status', 'removed')->count(),
            'total_announcements' => Announcement::count(),
            'total_applications' => Application::count(),
            'total_accommodations' => Accommodation::count(),
            'total_payments' => Payment::where('status', 'completed')->sum('amount'),
        ];

        $recentUsers = User::latest()->take(6)->get();
        $pendingLandlords = User::where('role', 'landlord')
            ->whereIn('landlord_verification_status', ['pending', 'needs_more_info'])
            ->with('landlordVerificationDocuments')
            ->latest()
            ->take(5)
            ->get();
        $pendingProperties = Property::whereIn('review_status', ['pending', 'changes_requested'])
            ->with('landlord')
            ->latest()
            ->take(5)
            ->get();
        $recentAnnouncements = Announcement::with('creator')
            ->latest()
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'pendingLandlords',
            'pendingProperties',
            'recentAnnouncements'
        ));
    }

    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('student_id', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        }

        $users = $query->latest()->paginate(12)->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:student,landlord,welfare,admin',
            'password' => ['required', 'confirmed', Password::defaults()],
            'student_id' => 'nullable|string|max:20|unique:users,student_id',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create($this->buildUserPayload($validated, $request));

        SystemNotification::notifyUser(
            $user->id,
            'Account created',
            'An administrator created your portal account.',
            route('dashboard'),
            'info',
            Auth::id()
        );

        return redirect()->route('admin.users')
            ->with('success', 'User account created successfully.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:student,landlord,welfare,admin',
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'student_id' => 'nullable|string|max:20|unique:users,student_id,' . $user->id,
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $originalStatus = $user->is_active;

        $payload = $this->buildUserPayload($validated, $request, $user);
        if (empty($validated['password'])) {
            unset($payload['password']);
        }

        $user->update($payload);

        if ($user->id !== Auth::id()) {
            $message = $originalStatus !== $user->is_active
                ? 'Your account status was updated by an administrator.'
                : 'Your account details were updated by an administrator.';

            SystemNotification::notifyUser(
                $user->id,
                'Account updated',
                $message,
                route('dashboard'),
                $user->is_active ? 'info' : 'warning',
                Auth::id()
            );
        }

        return redirect()->route('admin.users')
            ->with('success', 'User account updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->id === Auth::id(), 422, 'You cannot delete your own account.');

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'User account deleted successfully.');
    }

    public function landlordVerifications(Request $request): View
    {
        $query = User::where('role', 'landlord')->with('landlordVerificationDocuments');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('landlord_verification_status', $request->status);
        } else {
            $query->whereIn('landlord_verification_status', ['pending', 'needs_more_info', 'verified', 'rejected']);
        }

        $landlords = $query->latest()->paginate(10)->withQueryString();

        return view('admin.landlord-verifications', compact('landlords'));
    }

    public function processLandlordVerification(Request $request, User $landlord): RedirectResponse
    {
        abort_unless($landlord->isLandlord(), 404);

        $validated = $request->validate([
            'action' => 'required|in:approve,reject,request_more_info',
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
            'status' => match ($validated['action']) {
                'approve' => 'verified',
                'request_more_info' => 'more_info_required',
                default => 'rejected',
            },
            'review_notes' => $validated['notes'],
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        if ($validated['action'] === 'reject') {
            $landlord->update([
                'landlord_verification_status' => 'rejected',
                'verification_notes' => $validated['notes'],
                'landlord_verified_at' => null,
                'landlord_verification_reviewed_by' => Auth::id(),
                'landlord_verification_reviewed_at' => now(),
            ]);

            $this->notifyLandlordVerificationDecision(
                $landlord,
                'Landlord verification rejected',
                'Your landlord verification was rejected. Review the notes and resubmit the required document.'
            );

            return back()->with('success', 'Landlord verification rejected.');
        }

        if ($validated['action'] === 'request_more_info') {
            $landlord->update([
                'landlord_verification_status' => 'needs_more_info',
                'landlord_verification_stage' => $currentStage,
                'verification_notes' => $validated['notes'],
                'landlord_verified_at' => null,
                'landlord_verification_reviewed_by' => Auth::id(),
                'landlord_verification_reviewed_at' => now(),
            ]);

            $this->notifyLandlordVerificationDecision(
                $landlord,
                'More landlord verification information required',
                'An administrator requested more information for your ' . str_replace('_', ' ', $currentStage) . ' stage.'
            );

            return back()->with('success', 'More information requested from landlord.');
        }

        $stages = array_keys($landlord->landlordVerificationSteps());
        $currentIndex = array_search($currentStage, $stages, true);
        $nextStage = $stages[$currentIndex + 1] ?? null;

        if ($nextStage) {
            $landlord->update([
                'landlord_verification_status' => 'pending',
                'landlord_verification_stage' => $nextStage,
                'verification_notes' => $validated['notes'],
                'landlord_verified_at' => null,
                'landlord_verification_reviewed_by' => Auth::id(),
                'landlord_verification_reviewed_at' => now(),
            ]);

            $this->notifyLandlordVerificationDecision(
                $landlord,
                'Landlord verification advanced',
                'Your current verification stage was approved. The next review stage is ' . str_replace('_', ' ', $nextStage) . '.'
            );

            return back()->with('success', 'Verification stage approved and advanced.');
        }

        $landlord->update([
            'landlord_verification_status' => 'verified',
            'landlord_verification_stage' => 'completed',
            'landlord_verified_at' => now(),
            'verification_notes' => $validated['notes'],
            'landlord_verification_reviewed_by' => Auth::id(),
            'landlord_verification_reviewed_at' => now(),
        ]);

        $this->notifyLandlordVerificationDecision(
            $landlord,
            'Landlord verification approved',
            'Your landlord account is now fully verified and you may advertise accommodation.'
        );

        return back()->with('success', 'Landlord fully verified.');
    }

    public function pendingProperties(Request $request): View
    {
        $query = Property::with('landlord');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('review_status', $request->status);
        } else {
            $query->whereIn('review_status', ['pending', 'changes_requested', 'approved', 'rejected', 'removed']);
        }

        $properties = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pending-properties', compact('properties'));
    }

    public function reviewProperty(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,request_changes,reject,remove',
            'notes' => 'nullable|string|max:3000',
            'suspend_landlord' => 'nullable|boolean',
        ]);

        $property->update([
            'is_approved' => $validated['action'] === 'approve',
            'is_available' => $validated['action'] === 'remove' ? false : $property->available_units > 0,
            'review_status' => match ($validated['action']) {
                'approve' => 'approved',
                'request_changes' => 'changes_requested',
                'remove' => 'removed',
                default => 'rejected',
            },
            'review_notes' => $validated['notes'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'listed_at' => $validated['action'] === 'approve' ? ($property->listed_at ?? now()) : $property->listed_at,
        ]);

        if ($validated['action'] === 'remove' && $request->boolean('suspend_landlord')) {
            $property->landlord?->update(['is_active' => false]);
        }

        $message = match ($validated['action']) {
            'approve' => 'Your property listing was approved and is now visible to students.',
            'request_changes' => 'Your property listing needs changes before it can go live.',
            'remove' => 'Your property listing was removed by an administrator.',
            default => 'Your property listing was rejected by an administrator.',
        };

        if ($property->landlord_id) {
            SystemNotification::notifyUser(
                $property->landlord_id,
                'Property review update',
                $message,
                route('landlord.properties'),
                $validated['action'] === 'approve' ? 'success' : 'warning',
                Auth::id()
            );
        }

        return redirect()->route('admin.properties.pending')
            ->with('success', 'Property review decision recorded successfully.');
    }

    public function announcements(): View
    {
        $announcements = Announcement::with('creator')->latest()->paginate(10);

        return view('admin.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'target_role' => 'nullable|in:student,landlord,welfare,admin',
            'priority' => 'required|in:info,warning,important',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_published' => 'nullable|boolean',
        ]);

        $announcement = Announcement::create([
            'created_by' => Auth::id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'target_role' => $validated['target_role'] ?? null,
            'priority' => $validated['priority'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? now(),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        if ($announcement->is_published && (!$announcement->published_at || $announcement->published_at->lte(now()))) {
            $this->notifyAnnouncementRecipients($announcement);
        }

        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement saved successfully.');
    }

    public function updateAnnouncement(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
            'target_role' => 'nullable|in:student,landlord,welfare,admin',
            'priority' => 'required|in:info,warning,important',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'is_published' => 'nullable|boolean',
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'target_role' => $validated['target_role'] ?? null,
            'priority' => $validated['priority'],
            'is_published' => $request->boolean('is_published'),
            'published_at' => $validated['published_at'] ?? $announcement->published_at ?? now(),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroyAnnouncement(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements')
            ->with('success', 'Announcement deleted successfully.');
    }

    private function buildUserPayload(array $validated, Request $request, ?User $existing = null): array
    {
        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'student_id' => $validated['student_id'] ?? null,
            'company_name' => $validated['company_name'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        if ($validated['role'] === 'landlord') {
            $payload['landlord_verification_status'] = $existing?->landlord_verification_status ?? 'pending';
            $payload['landlord_verification_stage'] = $existing?->landlord_verification_stage ?? 'company_registration';
        }

        return $payload;
    }

    private function notifyLandlordVerificationDecision(User $landlord, string $title, string $body): void
    {
        SystemNotification::notifyUser(
            $landlord->id,
            $title,
            $body,
            route('landlord.verification'),
            'info',
            Auth::id()
        );
    }

    private function notifyAnnouncementRecipients(Announcement $announcement): void
    {
        User::query()
            ->when($announcement->target_role, function ($query) use ($announcement) {
                $query->where('role', $announcement->target_role);
            })
            ->pluck('id')
            ->each(function (int $userId) use ($announcement) {
                SystemNotification::notifyUser(
                    $userId,
                    $announcement->title,
                    $announcement->content,
                    route('dashboard'),
                    $announcement->priority,
                    Auth::id()
                );
            });
    }
}
