<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Process;
use App\Models\ActualDate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\LogHelper;

class ActualProgressController extends Controller
{
    /**
     * Display a list of applications in progress.
     */
    public function index()
    {
        $applications = Application::where('status', 'In Progress')->get();
        return view('actual.index', compact('applications'));
    }

    /**
     * Show the form for editing actual progress of a specific application.
     */
    public function edit($id)
    {
        $application = Application::findOrFail($id);

        $groupedProcesses = Process::where('application_type', $application->application_type)
            ->orderBy('order')
            ->get()
            ->groupBy('major_process');

        $actualDates = ActualDate::where('application_id', $application->id)
            ->get()
            ->keyBy('process_id');

        return view('actual.edit', compact('application', 'groupedProcesses', 'actualDates'));
    }

    /**
     * Update the actual progress for submitted subprocesses.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $logs = [];

        foreach ($request->input('start_date', []) as $processId => $newStart) {
            $newEnd = $request->input('end_date', [])[$processId] ?? null;

            $record = ActualDate::firstOrNew([
                'application_id' => $application->id,
                'process_id'    => $processId,
            ]);

            $process = Process::find($processId);
            $subprocessName = $process?->sub_process ?? "Process {$processId}";
            $changes = [];

            // Normalize dates for comparison
            $oldStart = $record->start_date ? Carbon::parse($record->start_date)->format('Y-m-d') : null;
            $oldEnd   = $record->end_date   ? Carbon::parse($record->end_date)->format('Y-m-d') : null;
            $newStartNorm = $newStart ? Carbon::parse($newStart)->format('Y-m-d') : null;
            $newEndNorm   = $newEnd   ? Carbon::parse($newEnd)->format('Y-m-d') : null;

            // Only update if different
            if ($newStartNorm !== $oldStart) {
                $changes[] = "Start: " . ($oldStart ?? 'N/A') . " → " . ($newStartNorm ?? 'N/A');
                $record->start_date = $newStartNorm;
            }

            if ($newEndNorm !== $oldEnd) {
                $changes[] = "End: " . ($oldEnd ?? 'N/A') . " → " . ($newEndNorm ?? 'N/A');
                $record->end_date = $newEndNorm;
            }

            if (!empty($changes)) {
                $record->actual_duration = $this->calculateBusinessDays($record->start_date, $record->end_date);
                $record->save();

                $logs[] = "{$application->name} | {$subprocessName} | " . implode("; ", $changes);
            }
        }

        foreach ($logs as $log) {
            LogHelper::logAction('Actual Progress', 'update', $application->id, $log);
        }

        return redirect()
            ->route('actual.edit', $application->id)
            ->with('success', 'Actual progress updated successfully.');
    }


    /**
     * Format date for display.
     */
    private function formatDate($date): string
    {
        return $date ? Carbon::parse($date)->format('M d, Y') : 'N/A';
    }

    /**
     * Calculate business days between two dates (excluding weekends).
     */
    private function calculateBusinessDays($start, $end): int
    {
        if (!$start || !$end) return 0;

        $start = Carbon::parse($start);
        $end   = Carbon::parse($end);
        $days  = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) $days++;
        }

        return $days;
    }
}
