<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    // Table associated with the model
    protected $table = 'processes';

    // Mass assignable attributes
    protected $fillable = [
        'application_type',   // 'New Application', 'Renewal Application', 'Cancellation and Downgrading'
        'major_process',      // e.g., 'Visa Extension', 'AEP Application'
        'sub_process',        // e.g., 'Application Form, 2x2 ID picture, expiring AEP, passport bio-page'
        'duration_days',      // Number of days for this sub-process
        'order',              // Order of sub-process within major_process
    ];

    // Cast attributes to native types
    protected $casts = [
        'duration_days' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Relationship with the Application model.
     */
    public function application()
    {
        // Process is linked to Application indirectly through ActualDate
        return $this->hasManyThrough(Application::class, ActualDate::class);
    }


    /**
     * Relationship with ActualDate model (one-to-many).
     */
    public function actualDate()
    {
        return $this->hasMany(ActualDate::class);
    }
    

    /**
     * Scope to filter by application type.
     */
    public function scopeApplicationType($query, $type)
    {
        return $query->where('application_type', $type);
    }

    /**
     * Scope to filter by major process.
     */
    public function scopeMajorProcess($query, $major)
    {
        return $query->where('major_process', $major);
    }

    /**
     * Scope to order processes by `order`.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
