<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\BugReport;
use App\Models\FeatureSuggestion;
use App\Models\MedicineSale; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PharmacistController extends Controller
{
    public function dashboard()
    {
        $medicines = Medicine::where('user_id', Auth::id())->orderBy('name')->get();
        $lowStockMedicines = Medicine::where('user_id', Auth::id())->whereColumn('quantity', '<=', 'minimum_stock_level')->get();
        $expiringSoonMedicines = Medicine::where('user_id', Auth::id())->where('expiry_date', '<=', Carbon::now()->addDays(30))->get();
        $expiredMedicines = Medicine::where('user_id', Auth::id())->where('expiry_date', '<', Carbon::now())->get();

        return view('pharmacist.dashboard', compact(
            'medicines',
            'lowStockMedicines',
            'expiringSoonMedicines',
            'expiredMedicines'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'generic_name' => 'nullable|string|max:255',
                'manufacturer' => 'required|string|max:255',
                'quantity' => 'required|integer|min:0',
                'minimum_stock_level' => 'required|integer|min:0',
                'unit_price' => 'required|numeric|min:0',
                'expiry_date' => 'required|date|after:today',
                'batch_number' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:255',
                'is_public' => 'boolean',
                'symptoms_treated' => 'nullable|string',
            ]);

            Medicine::create(array_merge($request->all(), ['user_id' => Auth::id()]));

            return redirect()->route('pharmacist.dashboard')->with('success', 'Medicine added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput()->with('add_medicine_modal_open', true);
        }
    }

    public function update(Request $request, Medicine $medicine)
    {
        // Ensure the pharmacist can only update their own medicines
        if ($medicine->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'generic_name' => 'nullable|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'minimum_stock_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date',
            'batch_number' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'is_public' => 'boolean',
            'symptoms_treated' => 'nullable|string',
        ]);

        $medicine->update($request->all());

        return redirect()->route('pharmacist.dashboard')->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Medicine $medicine)
    {
        // Ensure the pharmacist can only delete their own medicines
        if ($medicine->user_id !== Auth::id()) {
            abort(403);
        }

        $medicine->delete();

        return redirect()->route('pharmacist.dashboard')->with('success', 'Medicine removed successfully!');
    }

    public function togglePublicStatus(Medicine $medicine)
    {
        // Ensure the pharmacist can only toggle status of their own medicines
        if ($medicine->user_id !== Auth::id()) {
            abort(403);
        }

        $medicine->is_public = !$medicine->is_public;
        $medicine->save();

        return back()->with('success', 'Medicine visibility updated successfully!');
    }

    public function showProfile()
    {
        return view('pharmacist.profile');
    }

    public function generateReport(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'daily':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
        }

        $medicines = Medicine::where('user_id', Auth::id())->whereBetween('created_at', [$startDate, $endDate])->get();
        $totalValue = $medicines->sum(function ($medicine) {
            return $medicine->quantity * $medicine->unit_price;
        });

        return view('pharmacist.reports', compact('medicines', 'totalValue', 'period', 'startDate', 'endDate'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'license_number' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();
        $user->update($request->only(['name', 'email', 'phone', 'license_number', 'address']));

        return redirect()->route('pharmacist.profile')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('pharmacist.profile')->with('success', 'Password updated successfully!');
    }

    public function storeBugReport(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
        ]);

        BugReport::create([
            'user_id' => Auth::id(),
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
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Feature suggestion submitted successfully!');
    }

    public function recordSale(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ]);

        $medicine = Medicine::find($request->medicine_id);

        if (!$medicine || $medicine->user_id !== Auth::id()) {
            return back()->with('error', 'Medicine not found or you do not have permission to sell this medicine.');
        }

        if ($medicine->quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Not enough stock available.'])->withInput();
        }

        // Create a new sale record
        MedicineSale::create([
            'medicine_id' => $medicine->id,
            'quantity' => $request->quantity,
            'sale_date' => $request->sale_date,
            'total_price' => $medicine->unit_price * $request->quantity,
            'pharmacist_id' => Auth::id(),
        ]);

        // Update medicine stock and sales count
        $medicine->decrement('quantity', $request->quantity);
        $medicine->increment('sales_count', $request->quantity);
        
        return back()->with('success', 'Sale recorded successfully!');
    }
}
