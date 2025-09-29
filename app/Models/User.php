<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPassword as ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Ensure this is included for notifications

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
        'password' => 'hashed',  // Laravel 10 and up automatically handles password hashing
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

    // Role helpers

    /**
     * Check if the user is GA Staff
     * 
     * @return bool
     */
    public function isGAStaff(): bool
    {
        return trim($this->role) === 'GA Staff';
    }

    /**
     * Check if the user is an Admin Expatriate
     * 
     * @return bool
     */
    public function isAdminExpatriate(): bool
    {
        return trim($this->role) === 'Admin Expatriate';
    }

    /**
     * Check if the user is an Expatriate
     * 
     * @return bool
     */
    public function isExpatriate(): bool
    {
        return trim($this->role) === 'Expatriate';
    }

    // Accessor & mutator to clean role

    /**
     * Set the role attribute (ensures no leading/trailing spaces).
     * 
     * @param string $value
     * @return void
     */
    public function setRoleAttribute($value): void
    {
        $this->attributes['role'] = trim($value);
    }

    /**
     * Get the role attribute (ensures no leading/trailing spaces).
     * 
     * @param string $value
     * @return string
     */
    public function getRoleAttribute($value): string
    {
        return trim($value);
    }

    /**
     * Get all applications associated with the user.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
