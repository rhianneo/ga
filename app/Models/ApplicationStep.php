<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStep extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'application_steps';

    // Fillable fields
    protected $fillable = [
        'application_type',   // New, Renewal, Cancellation and Downgrading
        'step_name',          // Name of the step
        'order',              // Sequence order
        'depends_on',         // Step ID it depends on (optional)
        'parallel_group',     // Group ID for parallel steps (optional)
        'required_docs',      // JSON array of required documents
    ];

    // Automatically cast JSON columns to array
    protected $casts = [
        'required_docs' => 'array',
    ];

    /**
     * A step can have many actual progress records.
     */
    public function actualProgress()
    {
        return $this->hasMany(ActualProgress::class, 'application_step_id');
    }

    /**
     * Optional: Get the dependent step
     */
    public function dependsOnStep()
    {
        return $this->belongsTo(ApplicationStep::class, 'depends_on');
    }
}
