<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;

class GanttController extends Controller
{
    public function index()
    {
        // Retrieve applications with processes ordered by major_process and order
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

        foreach ($applications as $application) {
            $tasks = [];

            foreach ($application->processes as $process) {
                $pivot = $process->pivot;

                // Determine start and end
                $start = $pivot->start_date;
                $end   = $pivot->end_date ?? $pivot->start_date; // use start if end missing

                // Skip exempted process
                if ($process->sub_process === "Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet)") {
                    continue;
                }

                // Determine progress: 100 if completed, 0 if ongoing
                $progress = $pivot->end_date ? 100 : 0;

                $tasks[] = [
                    'id'            => 'process-' . $process->id,
                    'name'          => $process->sub_process,
                    'start'         => $start,
                    'end'           => $end,
                    'progress'      => $progress,
                    'custom_class'  => $majorProcessColors[$process->major_process] ?? 'bg-gray-500',
                ];
            }

            $ganttData[$application->id] = [
                'name'      => $application->full_name,
                'type'      => $application->application_type,
                'position'  => $application->position,
                'expiry'    => $application->expiry_date->format('Y-m-d'),
                'tasks'     => $tasks,
            ];
        }

        return view('gantt.index', compact('applications', 'ganttData'));
    }
}
