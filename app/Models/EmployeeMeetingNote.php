<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMeetingNote extends Model
{
    protected $fillable = [
        'employee_id',
        'tl_id',
        'meeting_date',
        'quotation_not_sending',
        'images_not_sending',
        'followup_not_regular',
        'cannot_convince_customer',
        'not_providing_discount',
        'conversation_not_good',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
            'quotation_not_sending' => 'boolean',
            'images_not_sending' => 'boolean',
            'followup_not_regular' => 'boolean',
            'cannot_convince_customer' => 'boolean',
            'not_providing_discount' => 'boolean',
            'conversation_not_good' => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function teamLead()
    {
        return $this->belongsTo(User::class, 'tl_id');
    }
}
