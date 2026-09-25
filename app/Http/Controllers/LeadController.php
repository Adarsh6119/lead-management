<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadRemark;
use App\Models\Booking;
use App\Models\Accounting;
use App\Models\CabType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Lead::with(['employee', 'remarks'])->latest();

        if ($user->role === 'employee') {
            $query->where('employee_id', $user->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%{$s}%")
                  ->orWhere('mobile_no', 'like', "%{$s}%")
                  ->orWhere('pickup_city', 'like', "%{$s}%")
                  ->orWhere('destination', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'overdue_new') {
                $fiveDaysAgo = \Carbon\Carbon::now()->subDays(5)->toDateString();
                $query->where('status', 'New Lead')
                      ->whereDate('date_created', '<=', $fiveDaysAgo);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('employee_id') && $user->role !== 'employee') {
            $query->where('employee_id', $request->employee_id);
        }

        $leads = $query->paginate(15)->withQueryString();
        $employees = User::where('role', 'employee')->get();
        $cabTypes = CabType::where('is_active', true)->pluck('name');
        $sources = ['IVR', 'Missed Call', 'Offer Campaign', 'Website Enquiry', 'Direct Call', 'WhatsApp'];

        return view('leads.index', compact('leads', 'employees', 'cabTypes', 'sources'));
    }

    public function create()
    {
        $cabTypes = CabType::where('is_active', true)->pluck('name');
        $employees = User::where('role', 'employee')->get();
        $sources = ['IVR', 'Missed Call', 'Offer Campaign', 'Website Enquiry', 'Direct Call', 'WhatsApp'];
        return view('leads.create', compact('cabTypes', 'employees', 'sources'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_created' => 'required|date',
            'source' => 'required|string',
            'mobile_no' => 'required|string|max:15',
            'customer_name' => 'nullable|string|max:255',
            'pickup_city' => 'nullable|string|max:255',
            'destination' => 'nullable|string|max:255',
            'pickup_date' => 'nullable|date',
            'pickup_time' => 'nullable|string',
            'return_date' => 'nullable|date',
            'trip_type' => 'nullable|string',
            'cab_type' => 'nullable|string',
            'state' => 'nullable|string',
            'web_rate' => 'nullable|numeric',
            'discounted_rate' => 'nullable|numeric',
            'final_quoted_rate' => 'nullable|numeric',
            'offer_discount' => 'nullable|numeric',
            'remark' => 'nullable|string',
        ]);

        $user = Auth::user();
        $employeeId = ($user->role === 'employee') ? $user->id : ($request->employee_id ?? $user->id);
        $employee = User::find($employeeId);

        $lead = Lead::create([
            'date_created' => $request->date_created,
            'source' => $request->source,
            'mobile_no' => $request->mobile_no,
            'customer_name' => $request->customer_name,
            'pickup_city' => $request->pickup_city,
            'destination' => $request->destination,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'return_date' => $request->return_date,
            'trip_type' => $request->trip_type ?? 'one_way',
            'cab_type' => $request->cab_type,
            'state' => $request->state ?? 'Uttar Pradesh',
            'web_rate' => $request->web_rate ?? 0,
            'discounted_rate' => $request->discounted_rate ?? 0,
            'final_quoted_rate' => $request->final_quoted_rate ?? 0,
            'offer_discount' => $request->offer_discount ?? 0,
            'status' => $request->status ?? 'New Lead',
            'employee_id' => $employee->id,
            'employee_name' => $employee->name,
        ]);

        if ($request->filled('remark')) {
            LeadRemark::create([
                'lead_id' => $lead->id,
                'note' => $request->remark,
                'added_by' => $user->name,
                'user_id' => $user->id,
            ]);
        }

        return redirect()->route('leads.show', $lead->id)->with('success', 'Lead created successfully!');
    }

    public function show($id)
    {
        $lead = Lead::with(['employee', 'remarks' => function ($q) {
            $q->latest();
        }])->findOrFail($id);

        $user = Auth::user();
        if ($user->role === 'employee' && $lead->employee_id !== $user->id) {
            abort(403, 'Unauthorized access to this lead.');
        }

        // Check for duplicate leads with same mobile number
        $duplicateLeads = Lead::where('mobile_no', $lead->mobile_no)
            ->where('id', '!=', $lead->id)
            ->get();

        $cabTypes = CabType::where('is_active', true)->pluck('name');

        return view('leads.show', compact('lead', 'duplicateLeads', 'cabTypes'));
    }

    public function addRemark(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|min:2',
            'status' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);
        $user = Auth::user();

        LeadRemark::create([
            'lead_id' => $lead->id,
            'note' => $request->note,
            'added_by' => $user->name,
            'user_id' => $user->id,
        ]);

        if ($request->filled('status')) {
            $lead->update(['status' => $request->status]);
        }

        return back()->with('success', 'Remark added successfully!');
    }

    public function convertToBooking(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $user = Auth::user();

        $request->validate([
            'reporting_address' => 'required|string',
            'rate' => 'required|numeric|min:0',
            'advance_payment' => 'required|numeric|min:0',
            'payment_mode' => 'required|string',
            'driver_name' => 'nullable|string',
            'driver_mobile' => 'nullable|string',
            'cab_number' => 'nullable|string',
        ]);

        $bookingId = 'CRS-' . date('Ymd') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT);
        $estAmt = $request->rate;
        $advAmt = $request->advance_payment;
        $pendingAmt = max(0, $estAmt - $advAmt);

        $booking = Booking::create([
            'booking_id' => $bookingId,
            'lead_id' => $lead->id,
            'date' => date('Y-m-d'),
            'customer_name' => $lead->customer_name ?: 'Valued Customer',
            'mobile_no' => $lead->mobile_no,
            'pickup_city' => $lead->pickup_city,
            'destination' => $lead->destination,
            'pickup_date' => $lead->pickup_date,
            'pickup_time' => $lead->pickup_time,
            'return_date' => $lead->return_date,
            'trip_type' => $lead->trip_type ?? 'one_way',
            'cab_type' => $lead->cab_type,
            'reporting_address' => $request->reporting_address,
            'driver_name' => $request->driver_name,
            'driver_mobile' => $request->driver_mobile,
            'cab_number' => $request->cab_number,
            'rate' => $estAmt,
            'advance_payment' => $advAmt,
            'payment_mode' => $request->payment_mode,
            'booking_status' => 'Confirmed',
            'employee_id' => $lead->employee_id,
            'employee_name' => $lead->employee_name,
        ]);

        // Create Accounting record
        Accounting::create([
            'booking_id' => $booking->booking_id,
            'customer_name' => $booking->customer_name,
            'mobile_no' => $booking->mobile_no,
            'estimated_amount' => $estAmt,
            'advance' => $advAmt,
            'pending' => $pendingAmt,
            'customer_state' => $lead->state ?: 'Uttar Pradesh',
            'payment_mode' => $booking->payment_mode,
            'payment_status' => ($advAmt >= $estAmt) ? 'Fully Paid' : 'Advance Paid',
            'employee_id' => $lead->employee_id,
        ]);

        // Update lead status
        $lead->update(['status' => 'Confirm Booking']);

        LeadRemark::create([
            'lead_id' => $lead->id,
            'note' => "🎉 Lead converted to confirmed booking. Booking ID: {$bookingId}",
            'added_by' => $user->name,
            'user_id' => $user->id,
        ]);

        return redirect()->route('bookings.show', $booking->id)->with('success', "Booking confirmed! CRS Ticket: {$bookingId}");
    }

    public function checkDuplicate(Request $request)
    {
        $mobile = $request->query('mobile_no');
        if (!$mobile) {
            return response()->json(['exists' => false]);
        }

        $existingLeads = Lead::where('mobile_no', 'like', "%{$mobile}%")
            ->get(['id', 'customer_name', 'mobile_no', 'status', 'employee_name', 'date_created']);

        return response()->json([
            'exists' => $existingLeads->count() > 0,
            'leads' => $existingLeads,
        ]);
    }

    public function addTlNote(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHead()) {
            abort(403, 'Only Team Leads and Admins can add TL suggestions.');
        }

        $lead = Lead::findOrFail($id);
        $request->validate([
            'tl_note' => 'required|string',
        ]);

        $lead->update([
            'tl_note' => $request->tl_note,
            'tl_note_by' => $user->name,
            'tl_note_at' => now(),
        ]);

        return back()->with('success', 'TL Suggestion / Note saved successfully!');
    }
}
