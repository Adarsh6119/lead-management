<?php

namespace App\Http\Controllers;

use App\Models\Accounting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'accountant'])) {
            abort(403, 'Only Accountants and Admins can access Accounting & GST records.');
        }

        $query = Accounting::with('employee')->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_id', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('mobile_no', 'like', "%{$s}%")
                  ->orWhere('customer_state', 'like', "%{$s}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('state')) {
            $query->where('customer_state', $request->state);
        }

        $accountings = $query->paginate(15)->withQueryString();

        // Financial Totals
        $totals = [
            'estimated' => (clone $query)->sum('estimated_amount'),
            'advance' => (clone $query)->sum('advance'),
            'pending' => (clone $query)->sum('pending'),
            'gst_advance' => (clone $query)->sum('gst_on_advance'),
            'total_gst' => (clone $query)->sum('total_gst'),
            'igst' => (clone $query)->sum('igst_advance'),
            'cgst' => (clone $query)->sum('cgst_advance'),
            'sgst' => (clone $query)->sum('sgst_advance'),
        ];

        $businessState = config('app.business_state', 'Uttar Pradesh');

        return view('accounting.index', compact('accountings', 'totals', 'businessState'));
    }

    public function updateStatus(Request $request, $id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'accountant'])) {
            abort(403, 'Unauthorized.');
        }

        $acc = Accounting::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|string',
            'transaction_id' => 'nullable|string',
            'bank_reco_status' => 'nullable|boolean',
        ]);

        $acc->update([
            'payment_status' => $request->payment_status,
            'transaction_id' => $request->transaction_id,
            'bank_reco_status' => $request->boolean('bank_reco_status'),
        ]);

        return back()->with('success', 'Accounting & Payment record updated successfully!');
    }
}
