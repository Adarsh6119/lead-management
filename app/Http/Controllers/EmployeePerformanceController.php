<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lead;
use App\Models\Booking;
use App\Models\Accounting;
use App\Models\LeadRemark;
use App\Models\EmployeeTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeePerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHead()) {
            abort(403, 'Admin or Team Lead access required to view performance dashboard.');
        }

        $selectedMonth = (int) $request->input('month', date('n'));
        $selectedYear = (int) $request->input('year', date('Y'));
        $today = Carbon::today();

        // Get all sales employees
        $employees = User::where('role', 'employee')->get();

        $performanceData = [];
        $totalLeadsMonth = 0;
        $totalBookingsMonth = 0;
        $totalRevenueMonth = 0;
        $topPerformer = null;
        $maxConversion = -1;

        foreach ($employees as $emp) {
            // Target for month
            $target = EmployeeTarget::where('employee_id', $emp->id)
                ->where('month', $selectedMonth)
                ->where('year', $selectedYear)
                ->first();

            $leadTarget = $target ? $target->lead_target : 50;
            $bookingTarget = $target ? $target->booking_target : 10;
            $revenueTarget = $target ? (float) $target->revenue_target : 100000.00;

            // Leads in month
            $leadsQuery = Lead::where('employee_id', $emp->id)
                ->whereYear('date_created', $selectedYear)
                ->whereMonth('date_created', $selectedMonth);

            $totalLeads = (clone $leadsQuery)->count();
            $newLeads = (clone $leadsQuery)->where('status', 'New Lead')->count();
            $followUpLeads = (clone $leadsQuery)->where('status', 'Follow Up')->count();
            $confirmedBookings = (clone $leadsQuery)->where('status', 'Confirm Booking')->count();
            $cancelledLeads = (clone $leadsQuery)->where('status', 'Booking Cancelled')->count();
            $lostLeads = (clone $leadsQuery)->where('status', 'Close / Lost')->count();

            // Revenue generated from accounting in month
            $revenue = (float) Accounting::where('employee_id', $emp->id)
                ->whereYear('created_at', $selectedYear)
                ->whereMonth('created_at', $selectedMonth)
                ->sum('estimated_amount');

            // Fallback: sum from bookings table if accounting is empty
            if ($revenue == 0 && $confirmedBookings > 0) {
                $revenue = (float) Booking::where('employee_id', $emp->id)
                    ->whereYear('date', $selectedYear)
                    ->whereMonth('date', $selectedMonth)
                    ->sum('rate');
            }

            $conversionRate = $totalLeads > 0 ? round(($confirmedBookings / $totalLeads) * 100, 1) : 0;
            $leadPct = $leadTarget > 0 ? min(100, round(($totalLeads / $leadTarget) * 100, 1)) : 0;
            $bookingPct = $bookingTarget > 0 ? min(100, round(($confirmedBookings / $bookingTarget) * 100, 1)) : 0;
            $revenuePct = $revenueTarget > 0 ? min(100, round(($revenue / $revenueTarget) * 100, 1)) : 0;

            // Overall Score = Weighted average (30% leads, 40% bookings, 30% revenue)
            $overallScore = round(($leadPct * 0.3) + ($bookingPct * 0.4) + ($revenuePct * 0.3), 1);

            if ($conversionRate > $maxConversion && $totalLeads > 0) {
                $maxConversion = $conversionRate;
                $topPerformer = [
                    'name' => $emp->name,
                    'login_id' => $emp->login_id,
                    'conversion' => $conversionRate,
                    'bookings' => $confirmedBookings,
                ];
            }

            // ===== ACTIVE LEADS WITH FOLLOW-UPS & REMARKS =====

            $activeLeads = Lead::where('employee_id', $emp->id)
                ->whereIn('status', ['New Lead', 'Follow Up'])
                ->with(['remarks' => function ($q) {
                    $q->latest()->take(3);
                }])
                ->orderByRaw('CASE WHEN next_followup_date IS NULL THEN 1 ELSE 0 END, next_followup_date ASC')
                ->get();

            // Follow-up stats
            $overdueFollowups = Lead::where('employee_id', $emp->id)
                ->whereIn('status', ['New Lead', 'Follow Up'])
                ->whereNotNull('next_followup_date')
                ->where('next_followup_date', '<', $today)
                ->count();

            $todayFollowups = Lead::where('employee_id', $emp->id)
                ->whereIn('status', ['New Lead', 'Follow Up'])
                ->whereNotNull('next_followup_date')
                ->whereDate('next_followup_date', $today)
                ->count();

            $upcomingFollowups = Lead::where('employee_id', $emp->id)
                ->whereIn('status', ['New Lead', 'Follow Up'])
                ->whereNotNull('next_followup_date')
                ->where('next_followup_date', '>', $today)
                ->count();

            $recentRemarks = LeadRemark::where('user_id', $emp->id)
                ->with('lead:id,customer_name,mobile_no,status')
                ->latest()
                ->take(8)
                ->get();

            $totalLeadsMonth += $totalLeads;
            $totalBookingsMonth += $confirmedBookings;
            $totalRevenueMonth += $revenue;

            $performanceData[] = [
                'employee' => $emp,
                'target' => $target,
                'lead_target' => $leadTarget,
                'booking_target' => $bookingTarget,
                'revenue_target' => $revenueTarget,
                'total_leads' => $totalLeads,
                'new_leads' => $newLeads,
                'followup_leads' => $followUpLeads,
                'confirmed_bookings' => $confirmedBookings,
                'cancelled_leads' => $cancelledLeads,
                'lost_leads' => $lostLeads,
                'revenue' => $revenue,
                'conversion_rate' => $conversionRate,
                'lead_pct' => $leadPct,
                'booking_pct' => $bookingPct,
                'revenue_pct' => $revenuePct,
                'overall_score' => $overallScore,
                'active_leads' => $activeLeads,
                'active_leads_count' => $activeLeads->count(),
                'overdue_followups' => $overdueFollowups,
                'today_followups' => $todayFollowups,
                'upcoming_followups' => $upcomingFollowups,
                'recent_remarks' => $recentRemarks,
            ];
        }

        // Sort performance data by overall score descending
        usort($performanceData, fn($a, $b) => $b['overall_score'] <=> $a['overall_score']);

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('employees.performance', compact(
            'performanceData',
            'selectedMonth',
            'selectedYear',
            'months',
            'totalLeadsMonth',
            'totalBookingsMonth',
            'totalRevenueMonth',
            'topPerformer',
            'today'
        ));
    }

    public function updateTarget(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHead()) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2035',
            'lead_target' => 'required|integer|min:1',
            'booking_target' => 'required|integer|min:1',
            'revenue_target' => 'required|numeric|min:0',
        ]);

        EmployeeTarget::updateOrCreate(
            [
                'employee_id' => $id,
                'month' => $request->month,
                'year' => $request->year,
            ],
            [
                'lead_target' => $request->lead_target,
                'booking_target' => $request->booking_target,
                'revenue_target' => $request->revenue_target,
            ]
        );

        return redirect()->back()->with('success', 'Monthly target updated successfully for employee!');
    }

    public function meetingNotes(Request $request, $employeeId = null)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHead()) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employees = User::whereIn('role', ['employee', 'accountant', 'head'])->get();
        $selectedEmployee = $employeeId ? User::findOrFail($employeeId) : $employees->first();

        $meetingQuery = \App\Models\EmployeeMeetingNote::with('teamLead');
        if ($selectedEmployee) {
            $meetingQuery->where('employee_id', $selectedEmployee->id);
        }

        $meetingNotes = $meetingQuery->orderBy('meeting_date', 'desc')->get();

        // Calculate analytics graph counts for the 6 points
        $checkpointCounts = [
            'Quotation Not Sending' => $meetingNotes->where('quotation_not_sending', true)->count(),
            'Images Not Sending' => $meetingNotes->where('images_not_sending', true)->count(),
            'Followup Not Regular' => $meetingNotes->where('followup_not_regular', true)->count(),
            'Unable to Convince Customer' => $meetingNotes->where('cannot_convince_customer', true)->count(),
            'Not Providing Discount' => $meetingNotes->where('not_providing_discount', true)->count(),
            'Conversation Quality Low' => $meetingNotes->where('conversation_not_good', true)->count(),
        ];

        return view('employees.meeting_notes', compact(
            'employees',
            'selectedEmployee',
            'meetingNotes',
            'checkpointCounts'
        ));
    }

    public function storeMeetingNote(Request $request, $employeeId)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isHead()) {
            abort(403, 'Admin or Team Lead access required.');
        }

        $employee = User::findOrFail($employeeId);

        $request->validate([
            'meeting_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        \App\Models\EmployeeMeetingNote::create([
            'employee_id' => $employee->id,
            'tl_id' => $user->id,
            'meeting_date' => $request->meeting_date,
            'quotation_not_sending' => $request->boolean('quotation_not_sending'),
            'images_not_sending' => $request->boolean('images_not_sending'),
            'followup_not_regular' => $request->boolean('followup_not_regular'),
            'cannot_convince_customer' => $request->boolean('cannot_convince_customer'),
            'not_providing_discount' => $request->boolean('not_providing_discount'),
            'conversation_not_good' => $request->boolean('conversation_not_good'),
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', '1-on-1 Team Lead Meeting Note & Checkpoints saved successfully!');
    }
}
