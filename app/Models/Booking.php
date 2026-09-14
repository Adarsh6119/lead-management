<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_id', 'lead_id', 'date', 'customer_name', 'mobile_no',
        'pickup_city', 'destination', 'pickup_date', 'pickup_time',
        'return_date', 'trip_type', 'cab_type',
        'reporting_address', 'driver_name', 'driver_mobile', 'cab_number',
        'rate', 'advance_payment', 'payment_mode',
        'source_tag', 'booking_status', 'employee_id', 'employee_name',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'pickup_date' => 'date',
            'return_date' => 'date',
            'rate' => 'decimal:2',
            'advance_payment' => 'decimal:2',
        ];
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->booking_status) {
            'Confirmed' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'Completed' => 'bg-sky-100 text-sky-800 border border-sky-200',
            'Cancelled' => 'bg-rose-100 text-rose-800 border border-rose-200',
            default => 'bg-slate-100 text-slate-600 border border-slate-200',
        };
    }

    public function getPendingAmountAttribute(): float
    {
        return max(0, $this->rate - $this->advance_payment);
    }
}
