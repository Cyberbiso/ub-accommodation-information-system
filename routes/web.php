<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\WelfareController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\AccommodationHubController;
use App\Http\Controllers\InformationHubController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// LANDING PAGE (YOUR CUSTOM HOMEPAGE)
// ==========================================
Route::get('/', [LandingPageController::class, 'index'])->name('home');

// ==========================================
// PUBLIC INFORMATION PAGES
// ==========================================
Route::get('/about', [LandingPageController::class, 'about'])->name('about');
Route::get('/contact', [LandingPageController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('faq');
Route::post('/contact', [LandingPageController::class, 'submitContact'])->name('contact.submit');

// ==========================================
// HUB ROUTES (Public)
// ==========================================
Route::get('/accommodation-hub', [AccommodationHubController::class, 'index'])->name('accommodation.hub');
Route::get('/information-hub', [InformationHubController::class, 'index'])->name('information.hub');

// ==========================================
// ACCOMMODATION ROUTES (Public Browse)
// ==========================================
Route::get('/accommodations', [AccommodationHubController::class, 'onCampus'])->name('accommodations.index');
Route::get('/properties', [AccommodationHubController::class, 'offCampus'])->name('properties.index');
Route::get('/properties/{property}', [AccommodationHubController::class, 'showProperty'])->name('properties.show');

// ==========================================
// INFORMATION MODULE ROUTES (Public)
// ==========================================
Route::prefix('information')->name('information.')->group(function () {
    Route::get('/', [InformationHubController::class, 'index'])->name('index');
    Route::get('/campus-directory', [InformationHubController::class, 'campusDirectory'])->name('campus-directory');
    Route::get('/immigration', [InformationHubController::class, 'immigration'])->name('immigration');
    Route::get('/checklist', [InformationHubController::class, 'checklist'])->name('checklist');
    Route::get('/resources', [InformationHubController::class, 'resources'])->name('resources');
});

// ==========================================
// AUTHENTICATION ROUTES (from Breeze)
// ==========================================
require __DIR__.'/auth.php';

// ==========================================
// PROTECTED ROUTES (Require Login)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Breeze Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard Redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isStudent()) {
            return redirect()->route('student.dashboard');
        } elseif ($user->isLandlord()) {
            return redirect()->route('landlord.dashboard');
        } elseif ($user->isWelfare()) {
            return redirect()->route('welfare.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return view('dashboard');
    })->name('dashboard');
    
    // ==========================================
    // STUDENT ROUTES
    // ==========================================
    Route::prefix('student')->name('student.')->group(function () {
        // Home page (new)
        Route::get('/home', [StudentController::class, 'home'])->name('home');
        
        // Dashboard (legacy)
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        
        // Accommodation browsing
        Route::get('/accommodations', [StudentController::class, 'accommodations'])->name('accommodations');
        Route::get('/accommodations/{accommodation}', [StudentController::class, 'showAccommodation'])->name('accommodations.show');
        
        // Application form routes
        Route::get('/apply', [StudentController::class, 'showApplicationForm'])->name('apply.form');

// Application submission (without accommodation selection)
Route::post('/applications/store', [StudentController::class, 'storeApplication'])->name('applications.store');
        
        // Apply for specific accommodation (with ID)
        Route::post('/accommodations/{accommodation}/apply', [StudentController::class, 'apply'])->name('accommodations.apply');
        
        // Applications
        Route::get('/applications', [StudentController::class, 'applications'])->name('applications');
        Route::get('/applications/{application}', [StudentController::class, 'showApplication'])->name('applications.show');
        
        // Properties
        Route::get('/properties', [StudentController::class, 'properties'])->name('properties');
        Route::get('/properties/{property}', [StudentController::class, 'showProperty'])->name('properties.show');
        Route::post('/properties/{property}/viewing-request', [StudentController::class, 'requestViewing'])->name('viewing-request');
        
        // Viewing requests
        Route::get('/viewing-requests', [StudentController::class, 'viewingRequests'])->name('viewing-requests');
        
        // Payments
        Route::get('/payments', [StudentController::class, 'payments'])->name('payments');
    });
    
    // ==========================================
    // LANDLORD ROUTES
    // ==========================================
    Route::prefix('landlord')->name('landlord.')->group(function () {
        Route::get('/dashboard', [LandlordController::class, 'dashboard'])->name('dashboard');
        Route::get('/properties', [LandlordController::class, 'properties'])->name('properties');
        Route::get('/properties/create', [LandlordController::class, 'createProperty'])->name('properties.create');
        Route::post('/properties', [LandlordController::class, 'storeProperty'])->name('properties.store');
        Route::get('/properties/{property}/edit', [LandlordController::class, 'editProperty'])->name('properties.edit');
        Route::put('/properties/{property}', [LandlordController::class, 'updateProperty'])->name('properties.update');
        Route::delete('/properties/{property}', [LandlordController::class, 'destroyProperty'])->name('properties.destroy');
        Route::get('/viewing-requests', [LandlordController::class, 'viewingRequests'])->name('viewing-requests');
        Route::post('/viewing-requests/{viewingRequest}/approve', [LandlordController::class, 'approveRequest'])->name('viewing-requests.approve');
        Route::post('/viewing-requests/{viewingRequest}/reject', [LandlordController::class, 'rejectRequest'])->name('viewing-requests.reject');
    });
    
    // ==========================================
    // WELFARE OFFICER ROUTES
    // ==========================================
    Route::prefix('welfare')->name('welfare.')->group(function () {
        Route::get('/dashboard', [WelfareController::class, 'dashboard'])->name('dashboard');
        
        // Reports routes (used in dashboard cards)
        Route::get('/reports/occupancy', [WelfareController::class, 'occupancyOverview'])->name('reports.occupancy');
        Route::get('/applications/overview', [WelfareController::class, 'applications'])->name('applications.overview');
        
        // Occupancy routes
        Route::get('/occupancy', [WelfareController::class, 'occupancyOverview'])->name('occupancy.overview');
        
        // Document verification routes
        Route::get('/documents/pending', [WelfareController::class, 'pendingDocuments'])->name('documents.pending');
        Route::get('/documents/{document}/verify', [WelfareController::class, 'verifyDocumentForm'])->name('documents.verify');
        Route::post('/documents/{document}/verify', [WelfareController::class, 'verifyDocument'])->name('documents.verify.process');
        
        // Application routes
        Route::get('/applications', [WelfareController::class, 'applications'])->name('applications');
        Route::get('/applications/{application}', [WelfareController::class, 'showApplication'])->name('applications.show');
        Route::post('/applications/{application}/approve', [WelfareController::class, 'approveApplication'])->name('applications.approve');
        Route::post('/applications/{application}/reject', [WelfareController::class, 'rejectApplication'])->name('applications.reject');
        
        // Accommodation management routes
        Route::get('/accommodations', [WelfareController::class, 'accommodations'])->name('accommodations');
        Route::get('/accommodations/create', [WelfareController::class, 'createAccommodation'])->name('accommodations.create');
        Route::post('/accommodations', [WelfareController::class, 'storeAccommodation'])->name('accommodations.store');
        Route::get('/accommodations/{accommodation}/edit', [WelfareController::class, 'editAccommodation'])->name('accommodations.edit');
        Route::put('/accommodations/{accommodation}', [WelfareController::class, 'updateAccommodation'])->name('accommodations.update');
        
        // Filter route for accommodations by block
        Route::get('/accommodations/block/{block}', [WelfareController::class, 'accommodationsByBlock'])->name('accommodations.block');
    });
    
    // ==========================================
    // ADMIN ROUTES
    // ==========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/properties/pending', [AdminController::class, 'pendingProperties'])->name('properties.pending');
        Route::post('/properties/{property}/approve', [AdminController::class, 'approveProperty'])->name('properties.approve');
        Route::post('/properties/{property}/reject', [AdminController::class, 'rejectProperty'])->name('properties.reject');
    });
// TEST ROUTE - REMOVE AFTER TESTING
Route::post('/test-register', function(Request $request) {
    dd($request->all()); // This will dump all form data and stop execution
})->name('test.register');
});