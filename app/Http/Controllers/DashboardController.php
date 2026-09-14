<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Booking;
use App\Models\Accounting;
use App\Models\User;
use App\Models\CabType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Base Query
        $leadQuery = Lead::query();
        $bookingQuery = Booking::query();
        $accountingQuery = Accounting::query();

        // Employee restriction: regular employees only see their own assigned leads/bookings
        if ($user->role === 'employee') {
            $leadQuery->where('employee_id', $user->id);
            $bookingQuery->where('employee_id', $user->id);
            $accountingQuery->where('employee_id', $user->id);
        }

        // Apply Filters
        if ($request->filled('date_from')) {
            $leadQuery->whereDate('date_created', '>=', $request->date_from);
            $bookingQuery->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $leadQuery->whereDate('date_created', '<=', $request->date_to);
            $bookingQuery->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('cab_type')) {
            $leadQuery->where('cab_type', $request->cab_type);
            $bookingQuery->where('cab_type', $request->cab_type);
        }
        if ($request->filled('source')) {
            $leadQuery->where('source', $request->source);
        }
        if ($request->filled('employee_id') && $user->role !== 'employee') {
            $leadQuery->where('employee_id', $request->employee_id);
            $bookingQuery->where('employee_id', $request->employee_id);
            $accountingQuery->where('employee_id', $request->employee_id);
        }
        if ($request->filled('state')) {
            $leadQuery->where('state', $request->state);
            $accountingQuery->where('customer_state', $request->state);
        }

        // Dashboard Metrics
        $totalLeads = (clone $leadQuery)->count();
        $newLeads = (clone $leadQuery)->where('status', 'New Lead')->count();
        $followUpLeads = (clone $leadQuery)->where('status', 'Follow Up')->count();
        $confirmedBookings = (clone $leadQuery)->where('status', 'Confirm Booking')->count();
        $cancelledLeads = (clone $leadQuery)->where('status', 'Booking Cancelled')->count();
        $lostLeads = (clone $leadQuery)->where('status', 'Close / Lost')->count();

        $conversionRate = $totalLeads > 0 ? round(($confirmedBookings / $totalLeads) * 100, 1) : 0;

        // Financial Metrics
        $totalRevenue = (clone $accountingQuery)->sum('estimated_amount');
        $totalAdvance = (clone $accountingQuery)->sum('advance');
        $totalPending = (clone $accountingQuery)->sum('pending');
        $totalGst = (clone $accountingQuery)->sum('total_gst');
        $igstTotal = (clone $accountingQuery)->sum('igst_advance');
        $cgstTotal = (clone $accountingQuery)->sum('cgst_advance');
        $sgstTotal = (clone $accountingQuery)->sum('sgst_advance');

        // Recent Activity lists
        $recentLeads = (clone $leadQuery)->with('employee')->latest()->take(6)->get();
        $recentBookings = (clone $bookingQuery)->latest()->take(6)->get();

        // Source Breakdown
        $sourceBreakdown = (clone $leadQuery)
            ->selectRaw('source, count(*) as count')
            ->groupBy('source')
            ->pluck('count', 'source')
            ->toArray();

        // Dropdown options for filter bar
        $employees = User::where('role', 'employee')->get();
        $cabTypes = CabType::where('is_active', true)->pluck('name');
        $sources = ['IVR', 'Missed Call', 'Offer Campaign', 'Website Enquiry', 'Direct Call', 'WhatsApp'];
        $states = config('app.indian_states', ['Uttar Pradesh']);

        return view('dashboard', compact(
            'totalLeads', 'newLeads', 'followUpLeads', 'confirmedBookings',
            'cancelledLeads', 'lostLeads', 'conversionRate',
            'totalRevenue', 'totalAdvance', 'totalPending', 'totalGst',
            'igstTotal', 'cgstTotal', 'sgstTotal',
            'recentLeads', 'recentBookings', 'sourceBreakdown',
            'employees', 'cabTypes', 'sources', 'states'
        ));
    }
}
