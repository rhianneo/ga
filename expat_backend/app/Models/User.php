<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Attributes to hide from array or JSON
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Attribute casting
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Helper methods for checking user roles.
     *
     * @return bool
     */
    public function isGAStaff(): bool
    {
        return $this->role === 'GA Staff';
    }

    /**
     * Check if the user is an Admin Expatriate.
     *
     * @return bool
     */
    public function isAdminExpatriate(): bool
    {
        return $this->role === 'Admin Expatriate';
    }

    /**
     * Check if the user is an Expatriate.
     *
     * @return bool
     */
    public function isExpatriate(): bool
    {
        return $this->role === 'Expatriate';
    }

    /**
     * Set the user's role attribute, trimming any extra spaces.
     *
     * @param string $value
     * @return void
     */
    public function setRoleAttribute($value): void
    {
        $this->attributes['role'] = trim($value);
    }

    /**
     * Get the user's role attribute, trimming any extra spaces.
     *
     * @param string $value
     * @return string
     */
    public function getRoleAttribute($value): string
    {
        return trim($value);
    }
}
