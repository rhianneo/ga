<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\LogHelper;

class ApplicationController extends Controller
{
    /**
     * Display a listing of applications with filters.
     */
    public function index(Request $request)
    {
        $years = Application::selectRaw('YEAR(expiry_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $applications = Application::query()->orderBy('expiry_date', 'asc');

        if ($request->filled('type')) {
            $applications->where('application_type', $request->type);
        }

        if ($request->filled('factory')) {
            $applications->where('factory', $request->factory);
        }

        if ($request->filled('year')) {
            $applications->whereYear('expiry_date', $request->year);
        }

        if ($request->filled('progress')) {
            $applications->where('status', $request->progress);
        }

        return view('applications.index', [
            'applications' => $applications->paginate(20),
            'years'        => $years
        ]);
    }

    /**
     * Show the form for creating a new application.
     */
    public function create()
    {
        return view('applications.create');
    }

    /**
     * Store a newly created application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'factory'         => 'required|string|in:Device Factory,Medical Factory',
            'position'        => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'TIN'             => 'nullable|string|max:255',
            'AEP_number'      => 'nullable|string|max:255',
            'expiry_date'     => 'required|date',
            'status'          => 'required|string|in:Not Started,In Progress,Completed',
        ]);

        $expiryDate       = Carbon::parse($validated['expiry_date']);
        $followUpDate     = $expiryDate->copy()->subDays(92);
        $daysBeforeExpiry = max(0, Carbon::now()->diffInDays($expiryDate, false));

        $application = Application::create([
            'name'              => $validated['name'],
            'application_type'  => $validated['type'],
            'factory'           => $validated['factory'],
            'position'          => $validated['position'],
            'passport_number'   => $validated['passport_number'],
            'TIN'               => $validated['TIN'],
            'AEP_number'        => $validated['AEP_number'],
            'expiry_date'       => $expiryDate,
            'follow_up_date'    => $followUpDate,
            'days_before_expiry'=> $daysBeforeExpiry,
            'status'            => $validated['status'],
            'user_id'           => auth()->id(),
        ]);

        LogHelper::logAction('Application', 'create', $application->id, [
            'Created application' => $application->toArray()
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application created successfully.');
    }

    /**
     * Edit an application.
     */
    public function edit(Application $application)
    {
        return view('applications.edit', compact('application'));
    }

    /**
     * Update an application.
     */
    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'factory'         => 'required|string|in:Device Factory,Medical Factory',
            'position'        => 'required|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'TIN'             => 'nullable|string|max:255',
            'AEP_number'      => 'nullable|string|max:255',
            'expiry_date'     => 'required|date',
            'status'          => 'required|string|in:Not Started,In Progress,Completed',
        ]);

        $oldData = $application->toArray();

        $expiryDate       = Carbon::parse($validated['expiry_date']);
        $followUpDate     = $expiryDate->copy()->subDays(92);
        $daysBeforeExpiry = max(0, Carbon::now()->diffInDays($expiryDate, false));

        $application->update([
            'name'              => $validated['name'],
            'application_type'  => $validated['type'],
            'factory'           => $validated['factory'],
            'position'          => $validated['position'],
            'passport_number'   => $validated['passport_number'],
            'TIN'               => $validated['TIN'],
            'AEP_number'        => $validated['AEP_number'],
            'expiry_date'       => $expiryDate,
            'follow_up_date'    => $followUpDate,
            'days_before_expiry'=> $daysBeforeExpiry,
            'status'            => $validated['status'],
        ]);

        $newData = $application->toArray();

        // Collect human-readable changes
        $changes = [];
        foreach ($newData as $field => $newValue) {
            if (in_array($field, ['id','user_id','created_at','updated_at','deleted_at'])) {
                continue; // skip system fields
            }

            $oldValue = $oldData[$field] ?? null;

            // Format dates for readability
            if (in_array($field, ['expiry_date', 'follow_up_date'])) {
                $oldValue = $oldValue ? Carbon::parse($oldValue)->format('Y-m-d') : 'N/A';
                $newValue = $newValue ? Carbon::parse($newValue)->format('Y-m-d') : 'N/A';
            } else {
                $oldValue = $oldValue ?: 'N/A';
                $newValue = $newValue ?: 'N/A';
            }

            if ($oldValue != $newValue) {
                $label = ucwords(str_replace('_', ' ', $field));
                $changes[] = "{$label} changed from \"{$oldValue}\" to \"{$newValue}\"";
            }
        }

        // Log changes with name
        if (!empty($changes)) {
            $description = $application->name . ': ' . implode('; ', $changes);

            LogHelper::logAction(
                'Application',
                'update',
                $application->id,
                $description
            );
        }

        return redirect()->route('applications.index')
            ->with('success', 'Application updated successfully.');
    }



    /**
     * Show an application details.
     */
    public function show(Application $application)
    {
        $followUpDate     = Carbon::parse($application->expiry_date)->subDays(92);
        $daysBeforeExpiry = Carbon::now()->diffInDays($application->expiry_date, false);

        return view('applications.show', compact('application', 'followUpDate', 'daysBeforeExpiry'));
    }

    /**
     * Delete an application.
     */
    public function destroy(Application $application)
    {
        $data = $application->toArray();
        $application->delete();

        LogHelper::logAction('Application', 'delete', $application->id, [
            'Deleted application' => $data
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application deleted successfully.');
    }
}
