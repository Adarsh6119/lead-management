<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accounting extends Model
{
    protected $fillable = [
        'booking_id', 'customer_name', 'mobile_no', 'estimated_amount',
        'advance', 'gst_on_advance', 'igst_advance', 'cgst_advance', 'sgst_advance',
        'pending', 'gst_on_pending', 'igst_pending', 'cgst_pending', 'sgst_pending',
        'total_amount', 'total_gst', 'customer_state', 'payment_mode',
        'transaction_id', 'bank_reco_status', 'payment_status', 'employee_id',
    ];

    protected function casts(): array
    {
        return [
            'estimated_amount' => 'decimal:2',
            'advance' => 'decimal:2',
            'gst_on_advance' => 'decimal:2',
            'igst_advance' => 'decimal:2',
            'cgst_advance' => 'decimal:2',
            'sgst_advance' => 'decimal:2',
            'pending' => 'decimal:2',
            'gst_on_pending' => 'decimal:2',
            'igst_pending' => 'decimal:2',
            'cgst_pending' => 'decimal:2',
            'sgst_pending' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'total_gst' => 'decimal:2',
            'bank_reco_status' => 'boolean',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($accounting) {
            $accounting->calculateGst();
        });
    }

    /**
     * Auto-calculate GST fields based on customer state vs business state
     */
    public function calculateGst(): void
    {
        $businessState = config('app.business_state', 'Uttar Pradesh');
        $customerState = $this->customer_state ?: 'Uttar Pradesh';

        // Advance GST (5%) — GST is only applicable on advance amount received
        $this->gst_on_advance = round($this->advance * 0.05, 2);
        if (strcasecmp(trim($customerState), trim($businessState)) === 0) {
            $this->cgst_advance = round($this->gst_on_advance / 2, 2);
            $this->sgst_advance = round($this->gst_on_advance / 2, 2);
            $this->igst_advance = 0;
        } else {
            $this->igst_advance = $this->gst_on_advance;
            $this->cgst_advance = 0;
            $this->sgst_advance = 0;
        }

        // Pending amount (no GST on pending — GST only on advance received)
        $this->pending = max(0, $this->estimated_amount - $this->advance);
        $this->gst_on_pending = 0;
        $this->igst_pending = 0;
        $this->cgst_pending = 0;
        $this->sgst_pending = 0;

        $this->total_amount = $this->estimated_amount;
        $this->total_gst = $this->gst_on_advance;
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
