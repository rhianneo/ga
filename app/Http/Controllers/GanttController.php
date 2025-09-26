<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Log;  // Add the Log facade for debugging

class GanttController extends Controller
{
    /**
     * Show Gantt Chart page with applications in progress.
     */
    public function index()
    {
        // Retrieve applications with their associated processes
        $applications = Application::with(['processes' => function ($query) {
            $query->orderBy('major_process')->orderBy('order');
        }])->where('status', 'In Progress')
          ->orderBy('expiry_date')
          ->get();

        $ganttData = [];
        
        // Define colors for each major process
        $majorProcessColors = [
            'Visa Extension' => 'bg-blue-500',
            'AEP Application' => 'bg-green-500',
            'PV VISA Application' => 'bg-yellow-500',
            'Cancellation of PEZA Visa' => 'bg-red-500',
            'Downgrading of PEZA Visa' => 'bg-purple-500',
        ];

        // Loop through applications and their processes
        foreach ($applications as $application) {
            $tasks = [];

            foreach ($application->processes as $process) {
                $pivot = $process->pivot;

                // Only add process if start and end date are available
                if ($pivot->start_date && $pivot->end_date) {
                    // Assign the color based on the major process
                    $processColor = $majorProcessColors[$process->major_process] ?? 'bg-gray-500';  // Default color if not matched

                    // Debugging: Log the process color
                    Log::info("Process ID: {$process->id}, Major Process: {$process->major_process}, Assigned Color: {$processColor}");

                    $tasks[] = [
                        'id'            => 'process-' . $process->id,
                        'name'          => $process->sub_process,  // Display sub-process name
                        'start'         => $pivot->start_date,
                        'end'           => $pivot->end_date,
                        'progress'      => $pivot->actual_duration ? min(100, ($pivot->actual_duration / $process->duration_days) * 100) : 0,
                        'custom_class'  => $processColor,  // Assign color class for the task
                    ];
                }
            }

            // Prepare Gantt data for each application
            $ganttData[$application->id] = [
                'name'      => $application->full_name,
                'type'      => $application->application_type,
                'position'  => $application->position,
                'expiry'    => $application->expiry_date->format('Y-m-d'),
                'tasks'     => $tasks,  // Pass the tasks to the view
            ];
        }

        // Return the view with the applications and Gantt data
        return view('gantt.index', compact('applications', 'ganttData'));
    }
}
