<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadRemark extends Model
{
    protected $fillable = [
        'lead_id', 'note', 'added_by', 'user_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
