<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // Role helpers
    public function isGAStaff(): bool
    {
        return trim($this->role) === 'GA Staff';
    }

    public function isAdminExpatriate(): bool
    {
        return trim($this->role) === 'Admin Expatriate';
    }

    public function isExpatriate(): bool
    {
        return trim($this->role) === 'Expatriate';
    }

    // Accessor & mutator to clean role
    public function setRoleAttribute($value): void
    {
        $this->attributes['role'] = trim($value);
    }

    public function getRoleAttribute($value): string
    {
        return trim($value);
    }
}
