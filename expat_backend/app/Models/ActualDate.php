<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActualDate extends Model
{
    use HasFactory;

    // Table name is optional if it follows Laravel's naming convention
    protected $table = 'actual_date';

    // Mass assignable attributes
    protected $fillable = [
        'application_id',  // Foreign key referencing the Application model
        'process_id',      // Foreign key referencing the Process model
        'start_date',      // Start date of the process
        'end_date',        // End date of the process
        'actual_duration', // Duration the process actually took
    ];

    // Casts for dates to ensure they are stored and returned as Carbon instances
    protected $casts = [
        'start_date' => 'date',  // Cast start_date as a Carbon date
        'end_date' => 'date',    // Cast end_date as a Carbon date
    ];

    /**
     * Define the relationship with Process (belongs to).
     */
    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    /**
     * Define the relationship with Application (belongs to).
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Method to filter actual dates for a specific application.
     */
    public function actualDateForApplication()
    {
        return $this->where('application_id', request()->route('id'))->first(); // Limit by current application's ID
    }

    /**
     * Accessor to get the actual duration in days.
     */
    public function getActualDurationInDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date); // Calculate duration in days between start and end dates
    }
}
