<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable; // Import this


class Application extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'applications';

    protected $fillable = [
        'name',
        'application_type',
        'factory',
        'position',
        'passport_number',
        'AEP_number',
        'TIN',
        'validity_date',
        'expiry_date',
        'status',
        'follow_up_date',
        'days_before_expiry',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'follow_up_date' => 'date',
    ];

    /**
     * One Application has many processes (with pivot table actual_date).
     */
    public function processes()
    {
        return $this->belongsToMany(Process::class, 'actual_date', 'application_id', 'process_id')
            ->withPivot('start_date', 'end_date', 'actual_duration')
            ->withTimestamps()
            ->orderBy('processes.major_process')
            ->orderBy('processes.order');
    }

    /**
     * One Application has many actual dates (direct access).
     */
    public function actualDate()
    {
        return $this->hasMany(ActualDate::class, 'application_id');
    }

    /**
     * Accessor: Properly formatted name.
     */
    public function getFullNameAttribute()
    {
        return ucwords(strtolower($this->name));
    }

    /**
     * Accessor: Calculate days remaining before expiry.
     *
     * @return int|null
     */
    public function getDaysBeforeExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;  // If expiry_date is not set, return null or use a default value like 0
        }

        return Carbon::today()->diffInDays($this->expiry_date->startOfDay(), false);
    }

    /**
     * Query Scopes
     */

    /**
     * Scope to filter applications by application type.
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
     * Scope to filter applications expiring soon (within a given number of days).
     *
     * @param $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays($days));
    }

    /**
     * Automatically send notifications for expiring applications (90 or 100 days).
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($application) {
            // Only send reminders when the days before expiry is 90 or 100
            if ($application->days_before_expiry == 90 || $application->days_before_expiry == 100) {
                $reminderType = $application->days_before_expiry == 90 ? '90' : '100';
                $application->notify(new \App\Notifications\ExpiryReminder($application, $reminderType));
            }
        });
    }

    /**
     * Add validation for expiry_date and follow_up_date in model logic.
     * 
     * You can use Laravel's `nullable|date` rules to validate dates during form submissions.
     * This ensures that both dates are properly validated before being saved.
     */
    public static function rules()
    {
        return [
            'expiry_date' => 'nullable|date',
            'follow_up_date' => 'nullable|date',
        ];
    }
}
