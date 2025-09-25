<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $table = 'processes';

    protected $fillable = [
        'application_type',
        'major_process',
        'sub_process',
        'duration_days',
        'order',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Many-to-Many with Applications (via actual_date pivot).
     */
    public function applications()
    {
        return $this->belongsToMany(Application::class, 'actual_date', 'process_id', 'application_id')
            ->withPivot('start_date', 'end_date', 'actual_duration')
            ->withTimestamps();
    }

    /**
     * One Process has many actual date records (different applications).
     */
    public function actualDate()
    {
        return $this->hasMany(ActualDate::class, 'process_id');
    }

    /**
     * Query Scopes
     */
    public function scopeApplicationType($query, $type)
    {
        return $query->where('application_type', $type);
    }

    public function scopeMajorProcess($query, $major)
    {
        return $query->where('major_process', $major);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
