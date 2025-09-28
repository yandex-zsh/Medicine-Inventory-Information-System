<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
// use App\Mail\PharmacistApproved;
// use App\Mail\PharmacistRejected;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingPharmacists = User::where('role', 'pharmacist')->where('is_approved', false)->get();
        $approvedPharmacists = User::where('role', 'pharmacist')->where('is_approved', true)->get();
        $totalUsers = User::count();

        return view('admin.dashboard', compact('pendingPharmacists', 'approvedPharmacists', 'totalUsers'));
    }

    public function approvePharmacist(Request $request, User $user)
    {
        if ($user->role !== 'pharmacist') {
            return back()->with('error', 'Only pharmacists can be approved.');
        }

        $user->update([
            'is_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => null, // Clear any previous rejection reason
        ]);

        // TODO: Send approval email to pharmacist
        // Mail::to($user->email)->send(new PharmacistApproved($user));

        return back()->with('success', 'Pharmacist approved successfully.');
    }

    public function rejectPharmacist(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($user->role !== 'pharmacist') {
            return back()->with('error', 'Only pharmacists can be rejected.');
        }

        $user->update([
            'is_approved' => false,
            'approved_by' => Auth::id(),
            'approved_at' => null,
            'rejection_reason' => $request->rejection_reason,
        ]);

        // TODO: Send rejection email to pharmacist
        // Mail::to($user->email)->send(new PharmacistRejected($user, $request->rejection_reason));

        return back()->with('success', 'Pharmacist rejected successfully.');
    }

    public function viewUserActivityLogs()
    {
        // This is a placeholder. Real activity logs would require a separate logging system.
        // For now, we'll just return an empty view or a message.
        return view('admin.activity_logs');
    }

    public function viewBugReports()
    {
        $bugReports = \App\Models\BugReport::orderBy('created_at', 'desc')->get();
        return view('admin.bug_reports', compact('bugReports'));
    }

    public function viewFeatureSuggestions()
    {
        $featureSuggestions = \App\Models\FeatureSuggestion::orderBy('created_at', 'desc')->get();
        return view('admin.feature_suggestions', compact('featureSuggestions'));
    }

    // Method to change a user's role (e.g., from pharmacist to admin, or vice versa)
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,pharmacist',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

     public function viewPharmacistDetails(User $user)
    {
        if ($user->role !== 'pharmacist') {
            return back()->with('error', 'Only pharmacist details can be viewed.');
        }

        return view('admin.pharmacist_details', compact('user'));
    }

    public function deleteUser(User $user)
    {
        if ($user->isAdmin() && Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own admin account.');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
