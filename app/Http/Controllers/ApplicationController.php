<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ApplicationController extends Controller
{
    /**
     * Display a listing of applications with optional filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get distinct years based on expiry_date
        $years = Application::selectRaw('YEAR(expiry_date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year');

        // Start building the query for fetching applications
        $applications = Application::orderBy('expiry_date', 'asc');

        // Apply filters if they exist in the request
        if ($request->has('type') && !empty($request->type)) {
            $applications->where('application_type', $request->type);
        }

        if ($request->has('factory') && !empty($request->factory)) {
            $applications->where('factory', $request->factory);
        }

        if ($request->has('year') && !empty($request->year)) {
            // Filter by year using expiry_date
            $applications->whereYear('expiry_date', $request->year);
        }

        if ($request->has('progress') && !empty($request->progress)) {
            $applications->where('status', $request->progress);
        }

        // Paginate the results with 10 applications per page
        $applications = $applications->paginate(20);


        // Return the view with filtered applications, available years, and pagination
        return view('applications.index', compact('applications', 'years'));
    }

    /**
     * Show the form for creating a new application.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('applications.create');
    }

    /**
     * Store a newly created application in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'factory' => 'required|string|in:Device Factory,Medical Factory',
            'position' => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'TIN' => 'nullable|string|max:255',
            'AEP_number' => 'nullable|string|max:255',
            'expiry_date' => 'required|date',
            'status' => 'required|string|in:Not Started,In Progress,Completed',
        ]);

        // Calculate Follow-Up Date and Days Before Expiry
        $expiry_date = Carbon::parse($request->expiry_date);
        $follow_up_date = $expiry_date->copy()->subDays(92);
        $days_before_expiry = max(0, Carbon::now()->diffInDays($expiry_date, false));

        // Store the new application
        Application::create([
            'name' => $request->name,
            'application_type' => $request->type,
            'factory' => $request->factory,
            'position' => $request->position,
            'passport_number' => $request->passport_number,
            'TIN' => $request->TIN,
            'AEP_number' => $request->AEP_number,
            'expiry_date' => $expiry_date,
            'follow_up_date' => $follow_up_date,
            'days_before_expiry' => $days_before_expiry,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        // Redirect back to the applications list with a success message
        return redirect()->route('applications.index')->with('success', 'Application created successfully.');
    }

    /**
     * Show the form for editing the specified application.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\View\View
     */
    public function edit(Application $application)
    {
        return view('applications.edit', compact('application'));
    }

    /**
     * Update the specified application in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Application $application)
    {
        // Validate the incoming data
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'factory' => 'required|string|in:Device Factory,Medical Factory',
            'position' => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'TIN' => 'nullable|string|max:255',
            'AEP_number' => 'nullable|string|max:255',
            'expiry_date' => 'required|date',
            'status' => 'required|string|in:Not Started,In Progress,Completed',
        ]);

        // Calculate Follow-Up Date and Days Before Expiry
        $expiry_date = Carbon::parse($request->expiry_date);
        $follow_up_date = $expiry_date->copy()->subDays(92);
        $days_before_expiry = max(0, Carbon::now()->diffInDays($expiry_date, false));

        // Update the application
        $application->update([
            'name' => $request->name,
            'application_type' => $request->type,
            'factory' => $request->factory,
            'position' => $request->position,
            'passport_number' => $request->passport_number,
            'TIN' => $request->TIN,
            'AEP_number' => $request->AEP_number,
            'expiry_date' => $expiry_date,
            'follow_up_date' => $follow_up_date,
            'days_before_expiry' => $days_before_expiry,
            'status' => $request->status,
        ]);

        // Redirect back to the applications list with a success message
        return redirect()->route('applications.index')->with('success', 'Application updated successfully.');
    }

    /**
     * Display the specified application.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\View\View
     */
    public function show(Application $application)
    {
        // Calculate Follow-Up Date and Days Before Expiry for the given application
        $follow_up_date = Carbon::parse($application->expiry_date)->subDays(92);
        $days_before_expiry = Carbon::now()->diffInDays($application->expiry_date, false);

        return view('applications.show', compact('application', 'follow_up_date', 'days_before_expiry'));
    }

    /**
     * Remove the specified application from the database.
     *
     * @param \App\Models\Application $application
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Application $application)
    {
        // Delete the application
        $application->delete();

        // Redirect back to the applications list with a success message
        return redirect()->route('applications.index')->with('success', 'Application deleted successfully.');
    }
}
