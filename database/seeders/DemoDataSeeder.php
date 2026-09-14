<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\LeadRemark;
use App\Models\Booking;
use App\Models\Accounting;
use App\Models\User;
use App\Models\CabType;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $employees = User::whereIn('role', ['employee', 'head', 'admin'])->get();
        $cabTypes = CabType::pluck('name')->toArray();
        $sources = ['IVR', 'Missed Call', 'Offer Campaign', 'Website Enquiry', 'Direct Call', 'WhatsApp'];
        $cities = ['Varanasi', 'Lucknow', 'Delhi', 'Ayodhya', 'Prayagraj', 'Gorakhpur', 'Agra', 'Jaipur', 'Patna'];
        $statuses = ['New Lead', 'Follow Up', 'Confirm Booking', 'Booking Cancelled', 'Close / Lost'];

        $sampleCustomers = [
            ['name' => 'Rajesh Sharma', 'mobile' => '9812345678', 'city' => 'Varanasi', 'dest' => 'Ayodhya', 'cab' => 'Sedan (Dzire / Etios)'],
            ['name' => 'Sunita Agarwal', 'mobile' => '9723456789', 'city' => 'Lucknow', 'dest' => 'Varanasi', 'cab' => 'Premium SUV (Innova Crysta)'],
            ['name' => 'Vikram Malhotra', 'mobile' => '9634567890', 'city' => 'Delhi', 'dest' => 'Agra', 'cab' => 'SUV (Ertiga / XL6)'],
            ['name' => 'Anita Srivastava', 'mobile' => '9545678901', 'city' => 'Prayagraj', 'dest' => 'Varanasi', 'cab' => 'Hatchback (WagonR / Indica)'],
            ['name' => 'Rameshwar Dubey', 'mobile' => '9456789012', 'city' => 'Varanasi', 'dest' => 'Gorakhpur', 'cab' => 'Hire Driver Only'],
            ['name' => 'Preeti Singh', 'mobile' => '9367890123', 'city' => 'Ayodhya', 'dest' => 'Lucknow', 'cab' => 'Sedan (Dzire / Etios)'],
            ['name' => 'Amitabh Sen', 'mobile' => '9278901234', 'city' => 'Patna', 'dest' => 'Varanasi', 'cab' => 'Tempo Traveller (12 Seater)'],
            ['name' => 'Dr. K. N. Rao', 'mobile' => '9189012345', 'city' => 'Varanasi', 'dest' => 'Delhi', 'cab' => 'Luxury (Fortuner / BMW)'],
            ['name' => 'Gaurav Mishra', 'mobile' => '9090123456', 'city' => 'Varanasi', 'dest' => 'Prayagraj', 'cab' => 'Sedan (Dzire / Etios)'],
            ['name' => 'Sneha Kapoor', 'mobile' => '8901234567', 'city' => 'Lucknow', 'dest' => 'Delhi', 'cab' => 'SUV (Ertiga / XL6)'],
        ];

        foreach ($sampleCustomers as $index => $c) {
            $emp = $employees[$index % count($employees)];
            $status = $statuses[$index % count($statuses)];
            $dateCreated = Carbon::now()->subDays(rand(1, 15))->format('Y-m-d');
            $pickupDate = Carbon::now()->addDays(rand(1, 10))->format('Y-m-d');

            $lead = Lead::create([
                'date_created' => $dateCreated,
                'source' => $sources[$index % count($sources)],
                'mobile_no' => $c['mobile'],
                'customer_name' => $c['name'],
                'pickup_city' => $c['city'],
                'destination' => $c['dest'],
                'pickup_date' => $pickupDate,
                'pickup_time' => sprintf('%02d:00', rand(6, 20)),
                'cab_type' => $c['cab'],
                'state' => 'Uttar Pradesh',
                'status' => $status,
                'offer_discount' => rand(0, 1) ? rand(200, 1000) : 0,
                'employee_id' => $emp->id,
                'employee_name' => $emp->name,
            ]);

            // Add sample remark
            LeadRemark::create([
                'lead_id' => $lead->id,
                'note' => "Initial enquiry received via " . $lead->source . ". Quoted rate for " . $lead->cab_type,
                'added_by' => $emp->name,
            ]);

            if ($status === 'Follow Up') {
                LeadRemark::create([
                    'lead_id' => $lead->id,
                    'note' => "Customer called back. Negotiating on discount amount.",
                    'added_by' => $emp->name,
                ]);
            }

            // If confirmed booking, create Booking + Accounting entry
            if ($status === 'Confirm Booking') {
                $bookingId = 'CRS-' . date('Ymd') . '-' . str_pad($lead->id, 4, '0', STR_PAD_LEFT);
                $estAmt = rand(3500, 12000);
                $advAmt = rand(500, 2000);
                $pendingAmt = $estAmt - $advAmt;

                $booking = Booking::create([
                    'booking_id' => $bookingId,
                    'lead_id' => $lead->id,
                    'date' => Carbon::now()->format('Y-m-d'),
                    'customer_name' => $lead->customer_name,
                    'mobile_no' => $lead->mobile_no,
                    'pickup_city' => $lead->pickup_city,
                    'destination' => $lead->destination,
                    'pickup_date' => $lead->pickup_date,
                    'pickup_time' => $lead->pickup_time,
                    'cab_type' => $lead->cab_type,
                    'reporting_address' => "House #" . rand(1, 100) . ", Near Main Chauk, " . $lead->pickup_city,
                    'employee_name' => $lead->employee_name,
                    'driver_name' => 'Driver ' . rand(1, 20),
                    'driver_mobile' => '987' . rand(1000000, 9999999),
                    'cab_number' => 'UP 65 ' . chr(rand(65, 90)) . chr(rand(65, 90)) . ' ' . rand(1000, 9999),
                    'rate' => $estAmt,
                    'advance_payment' => $advAmt,
                    'payment_mode' => rand(0, 1) ? 'UPI' : 'Bank Transfer',
                    'booking_status' => 'Confirmed',
                    'employee_id' => $emp->id,
                ]);

                // Create accounting record
                $businessState = config('app.business_state', 'Uttar Pradesh');
                $customerState = $lead->state ?: 'Uttar Pradesh';

                $gstAdv = round($advAmt * 0.05, 2);
                $igstAdv = 0; $cgstAdv = 0; $sgstAdv = 0;

                if (strcasecmp(trim($customerState), trim($businessState)) === 0) {
                    $cgstAdv = round($gstAdv / 2, 2);
                    $sgstAdv = round($gstAdv / 2, 2);
                } else {
                    $igstAdv = $gstAdv;
                }

                // No GST on pending — GST only on advance received
                Accounting::create([
                    'booking_id' => $booking->booking_id,
                    'customer_name' => $booking->customer_name,
                    'mobile_no' => $booking->mobile_no,
                    'estimated_amount' => $estAmt,
                    'advance' => $advAmt,
                    'gst_on_advance' => $gstAdv,
                    'igst_advance' => $igstAdv,
                    'cgst_advance' => $cgstAdv,
                    'sgst_advance' => $sgstAdv,
                    'pending' => $pendingAmt,
                    'gst_on_pending' => 0,
                    'igst_pending' => 0,
                    'cgst_pending' => 0,
                    'sgst_pending' => 0,
                    'total_amount' => $estAmt,
                    'total_gst' => $gstAdv,
                    'customer_state' => $customerState,
                    'payment_mode' => $booking->payment_mode,
                    'payment_status' => rand(0, 1) ? 'Advance Paid' : 'Fully Paid',
                    'employee_id' => $emp->id,
                ]);
            }
        }
    }
}
