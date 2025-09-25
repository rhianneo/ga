<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;

class ProcessManagementController extends Controller
{
    /**
     * Display a listing of the processes grouped by application type and major process.
     */
    public function index(Request $request)
    {
        $appTypes = ['New Application', 'Renewal Application', 'Cancellation and Downgrading'];
        $activeType = $request->get('type', 'New Application');

        // Fetch processes for the active application type, ordered
        $processes = Process::where('application_type', $activeType)
            ->orderBy('major_process')
            ->orderBy('order')
            ->get();

        return view('process.index', compact('processes', 'appTypes', 'activeType'));
    }

    /**
     * Show the form for creating a new process.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'New Application'); // pre-select application type
        return view('process.create', compact('type'));
    }

    /**
     * Store a newly created process in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_type' => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'major_process' => 'required|string|max:255',
            'sub_process' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
        ]);

        Process::create($validated);

        return redirect()->route('process.index', ['type' => $validated['application_type']])
            ->with('success', 'Process added successfully.');
    }

    /**
     * Show the form for editing the specified process.
     */
    public function edit(Process $process)
    {
        return view('process.edit', compact('process'));
    }

    /**
     * Update the specified process in storage.
     */
    public function update(Request $request, Process $process)
    {
        $validated = $request->validate([
            // application_type is disabled on edit, so optional
            'major_process' => 'required|string|max:255',
            'sub_process' => 'nullable|string',
            'order' => 'required|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
        ]);

        $process->update($validated);

        return redirect()->route('process.index', ['type' => $process->application_type])
            ->with('success', 'Process updated successfully.');
    }

    /**
     * Remove the specified process from storage.
     */
    public function destroy(Process $process)
    {
        $appType = $process->application_type;
        $process->delete();

        return redirect()->route('process.index', ['type' => $appType])
            ->with('success', 'Process deleted successfully.');
    }
}
