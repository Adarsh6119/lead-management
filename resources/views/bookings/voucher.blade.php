<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRS Voucher - {{ $booking->booking_id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; padding: 20px; }
        .voucher { max-width: 800px; margin: 0 auto; background: #fff; border: 2px solid #334155; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #0f172a, #1e293b); color: #fff; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 22px; font-weight: 900; }
        .header .badge { background: #f59e0b; color: #0f172a; padding: 6px 16px; border-radius: 8px; font-weight: 900; font-size: 13px; }
        .header .sub { font-size: 11px; color: #94a3b8; margin-top: 2px; }
        .section { padding: 16px 24px; border-bottom: 1px solid #e2e8f0; }
        .section-title { font-size: 11px; font-weight: 900; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10px; }
        .grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .field label { font-size: 9px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        .field .value { font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 2px; }
        .field .value.highlight { color: #059669; }
        .field .value.danger { color: #e11d48; }
        .field .value.mono { font-family: monospace; }
        .payment-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 16px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; text-align: center; }
        .payment-box .amount { font-size: 22px; font-weight: 900; }
        .payment-box .label { font-size: 10px; font-weight: 800; color: #059669; text-transform: uppercase; }
        .footer { padding: 16px 24px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #94a3b8; }
        .signature-line { border-top: 1px dashed #cbd5e1; width: 180px; text-align: center; padding-top: 6px; font-size: 10px; font-weight: 700; color: #64748b; }
        .print-btn { position: fixed; top: 20px; right: 20px; background: #f59e0b; color: #0f172a; padding: 12px 24px; border: none; border-radius: 10px; font-weight: 900; font-size: 14px; cursor: pointer; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 100; }
        .print-btn:hover { background: #d97706; }
        @media print {
            .print-btn { display: none; }
            body { background: #fff; padding: 0; }
            .voucher { border: 1px solid #000; border-radius: 0; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨 Print Voucher</button>

    <div class="voucher">
        <div class="header">
            <div>
                <h1>🚕 TaxiCRM — CRS Booking Voucher</h1>
                <div class="sub">Varanasi HQ · Uttar Pradesh · Lead & Booking Management System</div>
            </div>
            <div class="badge">{{ $booking->booking_id }}</div>
        </div>

        <div class="section">
            <div class="section-title">Customer Information</div>
            <div class="grid">
                <div class="field">
                    <label>Customer Name</label>
                    <div class="value">{{ $booking->customer_name }}</div>
                </div>
                <div class="field">
                    <label>Mobile Number</label>
                    <div class="value mono">{{ $booking->mobile_no }}</div>
                </div>
                <div class="field">
                    <label>Booking Date</label>
                    <div class="value">{{ $booking->date?->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Trip Details</div>
            <div class="grid">
                <div class="field">
                    <label>Pickup City</label>
                    <div class="value">{{ $booking->pickup_city }}</div>
                </div>
                <div class="field">
                    <label>Destination</label>
                    <div class="value">{{ $booking->destination }}</div>
                </div>
                <div class="field">
                    <label>Cab Type</label>
                    <div class="value" style="color: #d97706;">{{ $booking->cab_type }}</div>
                </div>
                <div class="field">
                    <label>Pickup Date</label>
                    <div class="value">{{ $booking->pickup_date?->format('d M Y') }}</div>
                </div>
                <div class="field">
                    <label>Pickup Time</label>
                    <div class="value">{{ $booking->pickup_time ?: 'TBD' }}</div>
                </div>
                <div class="field">
                    <label>Return Date</label>
                    <div class="value">{{ $booking->return_date ? $booking->return_date->format('d M Y') : '—' }}</div>
                </div>
                <div class="field">
                    <label>Trip Type</label>
                    <div class="value" style="text-transform: uppercase;">{{ str_replace('_', ' ', $booking->trip_type ?? 'one_way') }}</div>
                </div>
                <div class="field">
                    <label>Booked By (Employee)</label>
                    <div class="value">{{ $booking->employee_name }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Reporting & Pickup Address</div>
            <div class="field">
                <div class="value" style="font-size: 13px;">{{ $booking->reporting_address ?: 'To Be Provided' }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Driver & Vehicle Assignment</div>
            <div class="grid">
                <div class="field">
                    <label>Driver Name</label>
                    <div class="value">{{ $booking->driver_name ?: 'Pending Assignment' }}</div>
                </div>
                <div class="field">
                    <label>Driver Mobile</label>
                    <div class="value mono">{{ $booking->driver_mobile ?: '—' }}</div>
                </div>
                <div class="field">
                    <label>Vehicle / Cab Number</label>
                    <div class="value mono">{{ $booking->cab_number ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Payment Summary</div>
            <div class="payment-box">
                <div>
                    <div class="label">Total Estimated Rate</div>
                    <div class="amount" style="color: #0f172a;">₹{{ number_format($booking->rate, 0) }}</div>
                </div>
                <div>
                    <div class="label">Advance Payment</div>
                    <div class="amount" style="color: #059669;">₹{{ number_format($booking->advance_payment, 0) }}</div>
                </div>
                <div>
                    <div class="label">Pending Balance</div>
                    <div class="amount" style="color: #e11d48;">₹{{ number_format($booking->pending_amount, 0) }}</div>
                </div>
            </div>
            <div style="margin-top: 8px; font-size: 11px; color: #64748b;">
                Payment Mode: <strong>{{ $booking->payment_mode }}</strong>
                @if($accounting)
                    · GST on Advance (5%): <strong>₹{{ number_format($accounting->gst_on_advance, 2) }}</strong>
                    @if($accounting->igst_advance > 0)
                        (IGST)
                    @else
                        (CGST: ₹{{ number_format($accounting->cgst_advance, 2) }} + SGST: ₹{{ number_format($accounting->sgst_advance, 2) }})
                    @endif
                @endif
            </div>
        </div>

        <div class="footer">
            <div>
                <div>Generated: {{ now()->format('d M Y, h:i A') }}</div>
                <div>Status: <strong>{{ $booking->booking_status }}</strong></div>
            </div>
            <div class="signature-line">
                Authorized Signature
            </div>
        </div>
    </div>
</body>
</html>
