<?php

namespace App\Http\Controllers;

use App\Models\LandlordVerificationDocument;
use App\Models\Property;
use App\Models\PropertyBooking;
use App\Models\StudentDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function showStudentDocument(Request $request, StudentDocument $document): BinaryFileResponse
    {
        $user = Auth::user();

        abort_unless(
            $document->user_id === $user->id || $user->isAdmin() || $user->isWelfare(),
            403
        );

        return $this->servePublicDocument($document->path, $document->original_name, $request->boolean('download'));
    }

    public function showLandlordVerificationDocument(Request $request, LandlordVerificationDocument $document): BinaryFileResponse
    {
        $user = Auth::user();

        abort_unless(
            $document->user_id === $user->id || $user->isAdmin() || $user->isWelfare(),
            403
        );

        return $this->servePublicDocument($document->path, $document->original_name, $request->boolean('download'));
    }

    public function showPropertyLease(Request $request, Property $property): BinaryFileResponse
    {
        $user = Auth::user();

        $canAccess = $user->isAdmin()
            || $user->isWelfare()
            || ($user->isLandlord() && $property->landlord_id === $user->id)
            || ($user->isStudent() && (
                ($property->is_approved && $property->review_status === 'approved')
                || $property->bookings()->where('student_id', $user->id)->exists()
            ));

        abort_unless($canAccess, 403);

        return $this->servePublicDocument(
            $property->lease_agreement_path,
            $property->lease_agreement_original_name,
            $request->boolean('download')
        );
    }

    public function showSignedLease(Request $request, PropertyBooking $booking): BinaryFileResponse
    {
        $user = Auth::user();

        abort_unless(
            $user->isAdmin()
            || $user->isWelfare()
            || $booking->student_id === $user->id
            || $booking->landlord_id === $user->id,
            403
        );

        return $this->servePublicDocument(
            $booking->signed_lease_path,
            $booking->signed_lease_original_name,
            $request->boolean('download')
        );
    }

    private function servePublicDocument(?string $path, ?string $name, bool $download = false): BinaryFileResponse
    {
        abort_unless($path && Storage::disk('public')->exists($path), 404);

        $absolutePath = Storage::disk('public')->path($path);
        $fileName = $name ?: basename($path);

        if ($download) {
            return response()->download($absolutePath, $fileName);
        }

        return response()->file($absolutePath, [
            'Content-Disposition' => 'inline; filename="' . addslashes($fileName) . '"',
        ]);
    }
}
