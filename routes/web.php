<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\PharmacistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('public.home');
Route::get('/medicines', [PublicController::class, 'browseMedicines'])->name('public.medicines');
Route::get('/medicines/{medicine}', [PublicController::class, 'showMedicine'])->name('public.medicines.show');
Route::post('/feedback', [PublicController::class, 'storeFeedback'])->name('public.feedback.store');
Route::post('/bug-report', [PublicController::class, 'storeBugReport'])->name('public.bug_report.store');
Route::post('/feature-suggestion', [PublicController::class, 'storeFeatureSuggestion'])->name('public.feature_suggestion.store');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // General Dashboard (Can be a landing page after login, redirecting based on role)
    Route::get('/dashboard', function () {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->isPharmacist()) {
            if (Auth::user()->is_approved) {
                return redirect()->route('pharmacist.dashboard');
            } else {
                // For unapproved pharmacists, we can redirect to a specific pending approval page
                return redirect()->route('auth.pending_approval');
            }
        }
        return view('dashboard'); // Default for other roles or unapproved pharmacists
    })->name('dashboard');

    // Pharmacist Dashboard Routes
    Route::middleware(['pharmacist.approved'])->prefix('pharmacist')->name('pharmacist.')->group(function () {
        Route::get('/dashboard', [PharmacistController::class, 'dashboard'])->name('dashboard');
        Route::get('/reports', [PharmacistController::class, 'generateReport'])->name('reports');
        Route::get('/profile', [PharmacistController::class, 'showProfile'])->name('profile');
        Route::put('/profile', [PharmacistController::class, 'updateProfile'])->name('profile.update');
        Route::put('/password', [PharmacistController::class, 'updatePassword'])->name('password.update');

        // Medicine management routes
        Route::post('/medicines', [PharmacistController::class, 'store'])->name('medicines.store');
        Route::put('/medicines/{medicine}', [PharmacistController::class, 'update'])->name('medicines.update');
        Route::delete('/medicines/{medicine}', [PharmacistController::class, 'destroy'])->name('medicines.destroy');
        Route::put('/medicines/{medicine}/toggle-public', [PharmacistController::class, 'togglePublicStatus'])->name('medicines.toggle_public');

        // Bug reports and Feature suggestions (pharmacist can submit)
        Route::post('/bug-reports', [PharmacistController::class, 'storeBugReport'])->name('bug_reports.store');
        Route::post('/feature-suggestions', [PharmacistController::class, 'storeFeatureSuggestion'])->name('feature_suggestions.store');

        // Sales route
        Route::post('/sales', [PharmacistController::class, 'recordSale'])->name('record_sale');
    });

    // Admin Dashboard Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/pharmacists/{user}', [AdminController::class, 'viewPharmacistDetails'])->name('pharmacists.show');
        Route::put('/pharmacists/{user}/approve', [AdminController::class, 'approvePharmacist'])->name('pharmacists.approve');
        Route::put('/pharmacists/{user}/reject', [AdminController::class, 'rejectPharmacist'])->name('pharmacists.reject');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
        Route::get('/bug-reports', [AdminController::class, 'viewBugReports'])->name('bug-reports');
        Route::get('/feature-suggestions', [AdminController::class, 'viewFeatureSuggestions'])->name('feature-suggestions');
        Route::get('/activity-logs', [AdminController::class, 'viewUserActivityLogs'])->name('activity-logs');

        // Additional admin routes as needed (e.g., system settings)
    });
});

// New route for pending approval
Route::get('/pending-approval', function () {
    return view('auth.pending_approval');
})->name('auth.pending_approval');
