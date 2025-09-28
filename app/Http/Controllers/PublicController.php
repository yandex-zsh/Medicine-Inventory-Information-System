<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\BugReport;
use App\Models\FeatureSuggestion;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index()
    {
        $medicines = Medicine::where('is_public', true)->orderBy('name')->get();
        $trendingMedicines = Medicine::where('is_public', true)->orderByDesc('views_count')->take(8)->get();
        $highSalesMedicines = Medicine::where('is_public', true)->orderByDesc('sales_count')->take(8)->get();
        return view('public.home', compact('medicines', 'trendingMedicines', 'highSalesMedicines'));
    }

    public function browseMedicines(Request $request)
    {
        $query = Medicine::where('is_public', true);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('generic_name', 'like', '%' . $search . '%')
                  ->orWhere('symptoms_treated', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $medicines = $query->orderBy('name')->paginate(12);
        $categories = Medicine::where('is_public', true)->distinct('category')->pluck('category');

        return view('public.medicines', compact('medicines', 'categories'));
    }

    public function showMedicine(Medicine $medicine)
    {
        if (!$medicine->is_public) {
            abort(404);
        }
        $medicine->incrementViews(); // Increment view count
        return view('public.medicine_details', compact('medicine'));
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'pharmacist_id' => 'nullable|exists:users,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        Feedback::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'pharmacist_id' => $request->pharmacist_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Feedback submitted successfully!');
    }

    public function storeBugReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        BugReport::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Bug report submitted successfully!');
    }

    public function storeFeatureSuggestion(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        FeatureSuggestion::create([
            'user_id' => Auth::check() ? Auth::id() : null,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Feature suggestion submitted successfully!');
    }
}
