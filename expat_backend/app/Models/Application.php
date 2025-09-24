<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Application extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'applications';

    // Mass assignable attributes
    protected $fillable = [
        'name',               // Name of the expatriate
        'application_type',   // Type of application (e.g., 'New Application', 'Renewal Application')
        'factory',            // Factory associated with the application (e.g., 'Medical', 'Device')
        'position',           // Position of the expatriate in the company
        'passport_number',    // Passport number
        'AEP_number',         // AEP (Alien Employment Permit) number
        'TIN',                // Tax Identification Number
        'validity_date',      // Date of validity of the application
        'expiry_date',        // Date of expiry of the application
        'status',             // Current status of the application (e.g., 'In Progress', 'Completed')
        'follow_up_date',     // Date to follow up on the application
        'days_before_expiry', // Days remaining before the application expires
    ];

    // Automatically cast dates to Carbon instances for ease of manipulation
    protected $casts = [
        'expiry_date' => 'date',       // Ensures expiry_date is cast to a Carbon instance
        'follow_up_date' => 'date',    // Ensures follow_up_date is cast to a Carbon instance
    ];

    /**
     * Define the relationship with the Process model (One-to-Many).
     * Each application can have multiple processes.
     */


    public function processes()
    {
        return $this->hasMany(Process::class);
    }

    public function actualDate()
    {
        return $this->hasManyThrough(ActualDate::class, Process::class);
    }


    /**
     * Get the days remaining before expiry.
     * This is a helper method to calculate the remaining days before the application expires.
     */
    public function getDaysBeforeExpiryAttribute()
    {
        return Carbon::parse($this->expiry_date)->diffInDays(Carbon::now());
    }

    /**
     * Scope to filter applications by type.
     */
    public function scopeApplicationType($query, $type)
    {
        return $query->where('application_type', $type);
    }

    /**
     * Scope to filter applications by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter applications by factory.
     */
    public function scopeFactory($query, $factory)
    {
        return $query->where('factory', $factory);
    }

    /**
     * Scope to get applications that are close to expiry.
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays($days));
    }

    /**
     * Accessor to format the name of the application.
     */
    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->name)); // Format the name with proper capitalization
    }
}
