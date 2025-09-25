<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ActualDate extends Model
{
    use HasFactory;

    protected $table = 'actual_date'; // singular table name

    protected $fillable = [
        'application_id',
        'process_id',
        'start_date',
        'end_date',
        'actual_duration',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'actual_duration' => 'integer',
    ];

    /**
     * ActualDate belongs to an Application.
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    /**
     * ActualDate belongs to a Process.
     */
    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    /**
     * Accessor: Calculate duration if not manually set.
     */
    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date));
        }
        return $this->actual_duration ?? 0;
    }
}
