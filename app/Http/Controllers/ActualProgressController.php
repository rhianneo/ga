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

        // Fetch all processes for this application type
        $processes = Process::where('application_type', $application->application_type)
                            ->get();

        // Group by Major Process and sort by order within each group
        $groupedProcesses = $processes->sortBy('order')->groupBy('major_process');

        // Fetch existing actual dates keyed by process_id
        $actualDates = ActualDate::where('application_id', $application->id)
                                 ->get()
                                 ->keyBy('process_id');

        return view('actual.edit', compact('application', 'groupedProcesses', 'actualDates'));
    }

    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $startDates = $request->input('start_date', []);
        $endDates   = $request->input('end_date', []);

        $processIds = array_unique(array_merge(array_keys($startDates), array_keys($endDates)));

        foreach ($processIds as $processId) {
            $start = $startDates[$processId] ?? null;
            $end   = $endDates[$processId] ?? null;

            $record = ActualDate::where('application_id', $application->id)
                                ->where('process_id', $processId)
                                ->first();

            if (!$record) {
                if (!$start && !$end) continue;

                ActualDate::create([
                    'application_id' => $application->id,
                    'process_id'     => $processId,
                    'start_date'     => $start,
                    'end_date'       => $end,
                    'actual_duration'=> $this->calculateBusinessDays($start, $end),
                ]);
            } else {
                $updateData = [];

                if (!is_null($start)) $updateData['start_date'] = $start;
                if (!is_null($end)) $updateData['end_date'] = $end;

                if (!empty($updateData)) {
                    $s = $updateData['start_date'] ?? $record->start_date;
                    $e = $updateData['end_date'] ?? $record->end_date;
                    $updateData['actual_duration'] = $this->calculateBusinessDays($s, $e);

                    $record->update($updateData);
                }
            }
        }

        return redirect()->route('actual.edit', $application->id)
                         ->with('success', 'Actual progress updated successfully.');
    }

    private function calculateBusinessDays($start, $end)
    {
        if (!$start || !$end) return 0;

        $start = Carbon::parse($start);
        $end   = Carbon::parse($end);
        $days = 0;

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (!$date->isWeekend()) $days++;
        }

        return $days;
    }
}
