<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'date_created', 'source', 'mobile_no', 'customer_name',
        'pickup_city', 'destination', 'pickup_date', 'pickup_time',
        'return_date', 'trip_type', 'cab_type', 'state',
        'web_rate', 'discounted_rate', 'final_quoted_rate', 'offer_discount',
        'quotation_sent', 'ticket_generated', 'app_download',
        'last_followup_date', 'next_followup_date',
        'status', 'employee_id', 'employee_name',
        'tl_note', 'tl_note_by', 'tl_note_at',
    ];

    protected function casts(): array
    {
        return [
            'date_created' => 'date',
            'pickup_date' => 'date',
            'return_date' => 'date',
            'last_followup_date' => 'date',
            'next_followup_date' => 'date',
            'tl_note_at' => 'datetime',
            'quotation_sent' => 'boolean',
            'ticket_generated' => 'boolean',
            'app_download' => 'boolean',
            'web_rate' => 'decimal:2',
            'discounted_rate' => 'decimal:2',
            'final_quoted_rate' => 'decimal:2',
            'offer_discount' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function remarks()
    {
        return $this->hasMany(LeadRemark::class)->orderBy('created_at', 'desc');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'New Lead' => 'bg-sky-100 text-sky-800 border border-sky-200',
            'Follow Up' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'Confirm Booking' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'Booking Cancelled' => 'bg-rose-100 text-rose-800 border border-rose-200',
            'Close / Lost' => 'bg-slate-200 text-slate-600 border border-slate-300',
            default => 'bg-slate-100 text-slate-600 border border-slate-200',
        };
    }
}
