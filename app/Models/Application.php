<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Application extends Model
{
    use HasFactory;

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
     */
    public function getDaysBeforeExpiryAttribute()
    {
        return Carbon::now()->diffInDays($this->expiry_date, false);
    }
    

    /**
     * Query Scopes
     */
    public function scopeApplicationType($query, $type)
    {
        return $query->where('application_type', $type);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFactory($query, $factory)
    {
        return $query->where('factory', $factory);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', Carbon::now()->addDays($days));
    }
}
