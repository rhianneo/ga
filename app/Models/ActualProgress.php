<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActualProgress extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'actual_progress';

    // Fillable fields for dynamic step tracking
    protected $fillable = [
        'application_id',
        'application_step_id',  // Tracks which step this progress belongs to
        'start_date',
        'end_date',
        'duration',             // Computed automatically in days
        'status',               // Not Started / In Progress / Completed
    ];

    // Automatically cast these to Carbon instances
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration' => 'integer',
    ];

    /**
     * Relation to Application
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * Relation to ApplicationStep
     */
    public function step()
    {
        return $this->belongsTo(ApplicationStep::class, 'application_step_id');
    }

    /**
     * Automatically calculate duration when end_date or start_date is set
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value;

        if (!empty($value) && !empty($this->attributes['start_date'])) {
            $start = Carbon::parse($this->attributes['start_date']);
            $end = Carbon::parse($value);
            $this->attributes['duration'] = $start->diffInDays($end);
        }
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value;

        if (!empty($value) && !empty($this->attributes['end_date'])) {
            $start = Carbon::parse($value);
            $end = Carbon::parse($this->attributes['end_date']);
            $this->attributes['duration'] = $start->diffInDays($end);
        }
    }
}
