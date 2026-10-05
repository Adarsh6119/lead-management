<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTarget extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'advance_target',
        'lead_target',
        'booking_target',
        'revenue_target',
    ];

    protected function casts(): array
    {
        return [
            'month' => 'integer',
            'year' => 'integer',
            'advance_target' => 'decimal:2',
            'lead_target' => 'integer',
            'booking_target' => 'integer',
            'revenue_target' => 'decimal:2',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
