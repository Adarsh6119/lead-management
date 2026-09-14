<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'login_id', 'email', 'role', 'password',
        'is_active', 'status', 'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }

    public function isHead(): bool
    {
        return in_array($this->role, ['head', 'tl', 'team_lead']);
    }

    public function isTeamLead(): bool
    {
        return $this->isHead();
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'employee_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'employee_id');
    }

    public function remarks()
    {
        return $this->hasMany(LeadRemark::class, 'user_id');
    }

    public function targets()
    {
        return $this->hasMany(EmployeeTarget::class, 'employee_id');
    }

    public function meetingNotes()
    {
        return $this->hasMany(EmployeeMeetingNote::class, 'employee_id')->orderBy('meeting_date', 'desc');
    }
}
