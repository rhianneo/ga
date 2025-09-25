<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class GanttController extends Controller
{
    /**
     * Show Gantt Chart page with applications in progress.
     */
    public function index()
    {
        $applications = Application::with(['processes' => function ($query) {
            $query->orderBy('major_process')->orderBy('order');
        }])->where('status', 'In Progress')
          ->orderBy('expiry_date')
          ->get();

        $ganttData = [];
        $majorProcessColors = [
            'Visa Extension' => 'bg-blue-500',
            'AEP Application' => 'bg-green-500',
            'PV VISA Application' => 'bg-yellow-500',
            'Cancellation of PEZA Visa' => 'bg-red-500',
            'Downgrading of PEZA Visa' => 'bg-purple-500',
        ];

        foreach ($applications as $application) {
            $tasks = [];

            foreach ($application->processes as $process) {
                $pivot = $process->pivot;

                if ($pivot->start_date && $pivot->end_date) {
                    // Color based on Major Process
                    $processColor = $majorProcessColors[$process->major_process] ?? 'bg-gray-500';

                    $tasks[] = [
                        'id'        => 'process-' . $process->id,
                        'name'      => $process->sub_process,  // Display only sub-process name
                        'start'     => $pivot->start_date,
                        'end'       => $pivot->end_date,
                        'progress'  => $pivot->actual_duration ? min(100, ($pivot->actual_duration / $process->duration_days) * 100) : 0,
                        'custom_class' => $processColor,  // Apply color to the Gantt bar
                    ];
                }
            }

            $ganttData[$application->id] = [
                'name'  => $application->full_name,
                'type'  => $application->application_type,
                'position' => $application->position,
                'expiry'   => $application->expiry_date->format('Y-m-d'),
                'tasks' => $tasks,
            ];
        }

        return view('gantt.index', compact('applications', 'ganttData'));
    }
}
