<?php

namespace App\Http\Controllers;

use App\Models\Process;
use Illuminate\Http\Request;
use App\Helpers\LogHelper;

class ProcessManagementController extends Controller
{
    public function index(Request $request)
    {
        $appTypes   = ['New Application', 'Renewal Application', 'Cancellation and Downgrading'];
        $activeType = $request->get('type', 'New Application');

        $processes = Process::where('application_type', $activeType)
            ->orderBy('major_process')
            ->orderBy('order')
            ->get();

        return view('process.index', compact('processes', 'appTypes', 'activeType'));
    }

    public function create(Request $request)
    {
        $type = $request->get('type', 'New Application');
        return view('process.create', compact('type'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_type' => 'required|string|in:New Application,Renewal Application,Cancellation and Downgrading',
            'major_process'    => 'required|string|max:255',
            'sub_process'      => 'nullable|string',
            'order'            => 'required|integer|min:1',
            'duration_days'    => 'nullable|integer|min:0',
        ]);

        $process = Process::create($validated);

        // ✅ Log create in human-readable format
        $processName = $process->major_process . 
            (!empty($process->sub_process) ? ' - ' . $process->sub_process : '');
        
        LogHelper::logAction(
            'Process Management',
            'create',
            $process->id,
            "Created process: {$processName}"
        );

        return redirect()->route('process.index', ['type' => $validated['application_type']])
            ->with('success', 'Process added successfully.');
    }

    public function edit(Process $process)
    {
        return view('process.edit', compact('process'));
    }

    public function update(Request $request, Process $process)
    {
        $validated = $request->validate([
            'major_process' => 'required|string|max:255',
            'sub_process'   => 'nullable|string',
            'order'         => 'required|integer|min:1',
            'duration_days' => 'nullable|integer|min:0',
        ]);

        $oldData = $process->toArray();
        $process->update($validated);

        LogHelper::logAction('Process Management', 'update', $process->id, [
            'Before' => $oldData,
            'After'  => $process->toArray()
        ]);

        return redirect()->route('process.index', ['type' => $process->application_type])
            ->with('success', 'Process updated successfully.');
    }

    public function destroy(Process $process)
    {
        $appType = $process->application_type;
        $processName = $process->major_process . 
            (!empty($process->sub_process) ? ' - ' . $process->sub_process : '');

        $process->delete();

        // ✅ Log delete in human-readable format
        LogHelper::logAction(
            'Process Management',
            'delete',
            $process->id,
            "Deleted process: {$processName}"
        );

        return redirect()->route('process.index', ['type' => $appType])
            ->with('success', 'Process deleted successfully.');
    }
}
