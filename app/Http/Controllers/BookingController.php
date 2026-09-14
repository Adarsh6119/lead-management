<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Accounting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Booking::with('employee')->latest();

        if ($user->role === 'employee') {
            $query->where('employee_id', $user->id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_id', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('mobile_no', 'like', "%{$s}%")
                  ->orWhere('pickup_city', 'like', "%{$s}%")
                  ->orWhere('destination', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Booking::with(['lead', 'employee'])->findOrFail($id);

        $user = Auth::user();
        if ($user->role === 'employee' && $booking->employee_id !== $user->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        $accounting = Accounting::where('booking_id', $booking->booking_id)->first();

        return view('bookings.show', compact('booking', 'accounting'));
    }

    public function voucher($id)
    {
        $booking = Booking::with(['lead', 'employee'])->findOrFail($id);
        $accounting = Accounting::where('booking_id', $booking->booking_id)->first();

        return view('bookings.voucher', compact('booking', 'accounting'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'driver_name' => 'nullable|string',
            'driver_mobile' => 'nullable|string',
            'cab_number' => 'nullable|string',
            'reporting_address' => 'nullable|string',
            'booking_status' => 'required|string',
        ]);

        $booking->update([
            'driver_name' => $request->driver_name,
            'driver_mobile' => $request->driver_mobile,
            'cab_number' => $request->cab_number,
            'reporting_address' => $request->reporting_address,
            'booking_status' => $request->booking_status,
        ]);

        return back()->with('success', 'Booking & Driver details updated successfully!');
    }
}
