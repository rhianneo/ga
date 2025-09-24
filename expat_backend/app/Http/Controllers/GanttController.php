<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Process;
use App\Models\ActualDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GanttController extends Controller
{
    public function index()
    {
        // Fetch applications with "IN PROGRESS" status
        $applications = Application::where('status', 'IN PROGRESS')
            ->orderBy('expiry_date', 'asc')
            ->get();

        // Prepare the data for tabs (Applications that are in progress)
        $tabs = $applications->pluck('name')->toArray();  // Using 'name' instead of 'application_name'

        return view('gantt.index', compact('tabs'));
    }

    public function show($name) // Fetch the application by 'name'
    {
        // Fetch the application by 'name' column
        $application = Application::where('name', $name)->first();  // 'name' is used here

        // Check if the application exists
        if (!$application) {
            abort(404, 'Application not found');
        }

        // Fetch related processes for this application
        $processes = Process::where('application_type', $application->application_type)
            ->orderBy('order')
            ->get();

        // Fetch actual start and end dates for processes
        $actualDates = ActualDate::whereIn('process_id', $processes->pluck('id'))->get();

        // Timeline data preparation (adjust dates for the Gantt chart view)
        $timelineStart = Carbon::now()->subWeek();  // Start one week before current date
        $timelineEnd = Carbon::parse($application->expiry_date)->addWeek(); // End one week after expiry date

        // Return the view with all the necessary data
        return view('gantt.show', compact('application', 'processes', 'actualDates', 'timelineStart', 'timelineEnd'));
    }
    

}
