<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Process;
use App\Models\ActualDate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ActualProgressController extends Controller
{
    public function index()
    {
        $applications = Application::where('status', 'In Progress')->get();

        return view('actual.index', compact('applications'));
    }

    public function edit($id)
    {
        $application = Application::findOrFail($id);

        // Fetch subprocesses for this application type, ordered by major process and order
        $processes = Process::where('application_type', $application->application_type)
                            ->orderBy('major_process')
                            ->orderBy('order')
                            ->get();

        // Load existing actual dates for this application and key by process_id
        $actualDates = ActualDate::where('application_id', $application->id)
                                 ->get()
                                 ->keyBy('process_id');

        return view('actual.edit', compact('application', 'processes', 'actualDates'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $startDates = $request->input('start_date', []);
        $endDates   = $request->input('end_date', []);

        // Gather all process IDs submitted (either start or end)
        $processIds = array_unique(array_merge(array_keys($startDates), array_keys($endDates)));

        foreach ($processIds as $processId) {
            // Normalize empty strings to null
            $start = isset($startDates[$processId]) && $startDates[$processId] !== '' ? $startDates[$processId] : null;
            $end   = isset($endDates[$processId]) && $endDates[$processId] !== '' ? $endDates[$processId] : null;

            // Fetch existing record (if any) for this application + process
            $record = ActualDate::where('application_id', $application->id)
                                ->where('process_id', $processId)
                                ->first();

            if (!$record) {
                // Only create if at least one date was provided
                if (!$start && !$end) {
                    continue;
                }

                $actualDuration = $this->calculateBusinessDays($start, $end);

                ActualDate::create([
                    'application_id' => $application->id,
                    'process_id'     => $processId,
                    'start_date'     => $start,
                    'end_date'       => $end,
                    'actual_duration'=> $actualDuration,
                ]);
            } else {
                // Update only provided fields (do not overwrite with null)
                $updateData = [];

                if (!is_null($start)) {
                    $updateData['start_date'] = $start;
                }

                if (!is_null($end)) {
                    $updateData['end_date'] = $end;
                }

                if (!empty($updateData)) {
                    // Recalculate actual_duration using updated or existing dates
                    $s = $updateData['start_date'] ?? $record->start_date;
                    $e = $updateData['end_date'] ?? $record->end_date;

                    $updateData['actual_duration'] = $this->calculateBusinessDays($s, $e);

                    $record->update($updateData);
                }
            }
        }

        // Return to the edit page so the user can see the saved values immediately.
        return redirect()->route('actual.edit', $application->id)
                         ->with('success', 'Actual progress updated successfully.');
    }

    /**
     * Calculate business days between two dates (exclude Saturday & Sunday).
     * Accepts nulls; returns 0 if either date missing.
     *
     * @param string|null $start
     * @param string|null $end
     * @return int
     */
    private function calculateBusinessDays($start, $end)
    {
        if (!$start || !$end) {
            return 0;
        }

        $start = Carbon::parse($start);
        $end   = Carbon::parse($end);

        $days = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) {
                $days++;
            }
        }

        return $days;
    }
}
