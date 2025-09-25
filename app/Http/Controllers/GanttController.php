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

        foreach ($applications as $application) {
            $tasks = [];

            foreach ($application->processes as $process) {
                $pivot = $process->pivot;

                if ($pivot->start_date && $pivot->end_date) {
                    $tasks[] = [
                        'id'        => 'process-' . $process->id,
                        'name'      => $process->major_process . ' - ' . $process->sub_process,
                        'start'     => $pivot->start_date,
                        'end'       => $pivot->end_date,
                        'progress'  => $pivot->actual_duration ? min(100, ($pivot->actual_duration / $process->duration_days) * 100) : 0,
                        'custom_class' => 'bar-blue',
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
