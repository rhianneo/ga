@extends('layouts.app')

@section('styles')
    <style>
        .gantt-timeline {
            position: relative;
            height: 50px;
            background-color: #f0f0f0;
            margin-top: 20px;
        }

        .gantt-bar {
            position: absolute;
            top: 0;
            height: 30px;
            background-color: #6fa3ef;
            border-radius: 5px;
            color: white;
            text-align: center;
            line-height: 30px;
        }

        .gantt-process-name {
            font-size: 12px;
            white-space: nowrap;
        }

        .table th {
            background-color: #f8f9fa;
        }
    </style>
@endsection

@section('content')
<div class="container">
    @if(isset($application))
        <h3>{{ $application->name }} - {{ $application->application_type }} ({{ $application->position }})</h3>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Process</th>
                            <th>Start Date</th>
                            <th>Duration (Days)</th>
                            <th>Timeline</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($processes as $process)
                            <tr>
                                <td>{{ $process->major_process }}: {{ $process->sub_process }}</td>
                                <td>
                                    @php
                                        $actualStart = $actualDates->where('process_id', $process->id)->first()->start_date ?? 'N/A';
                                    @endphp
                                    {{ $actualStart }}
                                </td>
                                <td>
                                    @php
                                        $duration = $actualDates->where('process_id', $process->id)->first()->actual_duration ?? 'N/A';
                                    @endphp
                                    {{ $duration }} days
                                </td>
                                <td>
                                    <!-- Gantt Chart Visualization -->
                                    <div class="gantt-timeline">
                                        @php
                                            $timelineStart = \Carbon\Carbon::parse($timelineStart);
                                            $timelineEnd = \Carbon\Carbon::parse($timelineEnd);
                                        @endphp
                                        <div class="gantt-bar" style="left: {{ $timelineStart->diffInDays($actualStart) * 20 }}px; width: {{ $duration * 20 }}px;">
                                            <span class="gantt-process-name">{{ $process->major_process }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p>No application found.</p>
    @endif
</div>
@endsection
