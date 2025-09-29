@extends('layouts.app')

@section('content')
<div class="container mx-auto p-2">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Gantt Chart</h1>

    <!-- Tabs -->
    <ul class="flex border-b mb-3 space-x-2" id="ganttTabs">
        @foreach($applications as $index => $application)
            <li>
                <a class="inline-block py-2 px-4 font-semibold border-l border-t border-r rounded-t
                    {{ $index === 0 ? 'active-tab' : 'inactive-tab' }}"
                   href="javascript:void(0);"
                   onclick="showTab({{ $application->id }}, event)">
                   {{ $application->full_name }}
                </a>
            </li>
        @endforeach
    </ul>

    <!-- Tab Contents -->
    @foreach($applications as $index => $application)
        <div id="tab-{{ $application->id }}" class="tab-content {{ $index === 0 ? '' : 'hidden' }}">
            
            <!-- Header Info -->
            <div class="mb-3 p-3 bg-gray-50 border rounded-md shadow-sm">
                <h2 class="font-semibold text-gray-800 text-base">
                    {{ $application->full_name }} – {{ $application->position }}
                </h2>
                <p class="text-xs text-gray-600">
                    Expiry: {{ $application->expiry_date->format('M d, Y') }} |
                    Days Before Expiry: 
                    <span class="{{ $application->days_before_expiry <= 60 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                        {{ max(0, (int) round($application->days_before_expiry)) }}
                    </span>
                </p>
            </div>

            <!-- Legend -->
            <div class="mb-4 p-3 bg-white border rounded-lg shadow-sm">
                <h3 class="text-sm font-semibold mb-2 text-gray-800">Legend ({{ $application->application_type }})</h3>
                <div class="flex flex-wrap gap-4 text-xs">
                    @php
                        $type = $application->application_type;
                        $legends = [];

                        if ($type === 'New Application') {
                            $legends = [
                                'Visa Extension' => 'bg-blue-500',
                                'New Application' => 'bg-green-500',
                                'PV Visa Application' => 'bg-yellow-500',
                            ];
                        } elseif ($type === 'Renewal Application') {
                            $legends = [
                                'New Application' => 'bg-green-500',
                                'PV Visa Application' => 'bg-yellow-500',
                            ];
                        } elseif ($type === 'Application Cancellation') {
                            $legends = [
                                'Cancellation of PEZA Visa' => 'bg-red-500',
                            ];
                        } elseif ($type === 'Downgrading') {
                            $legends = [
                                'Downgrading of PEZA Visa' => 'bg-purple-500',
                            ];
                        }
                    @endphp

                    @foreach($legends as $label => $colorClass)
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 rounded {{ $colorClass }}"></span>
                            <span class="text-gray-700">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Exemption Note --}}
                @if(in_array($application->position, ['President & CEO', 'Vice President & COO']))
                    <p class="mt-2 text-xs italic text-gray-500">
                        <span class="font-semibold"> This applicant is exempted from the process: Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet).</span>
                    </p>
                @endif
            </div>

            <!-- Gantt Chart -->
            <div id="gantt_chart_{{ $application->id }}" style="height: 400px;"></div>
        </div>
    @endforeach
</div>

<!-- Frappe Gantt JS + CSS -->
<script type="module" src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

@push('styles')
    <style>
        /* Tabs */
        .active-tab {
            background-color: #e0f7fa;
            color: #0288d1;
            border-color: #0288d1;
        }
        .inactive-tab {
            background-color: #fff;
            color: #607d8b;
        }

        /* Gantt Bar Colors (match legend) */
        .bar-wrapper.bg-blue-500 rect.bar,
        .bar-wrapper.bg-blue-500 rect.bar-progress { fill: #59ddd2ff !important; }
        .bar-wrapper.bg-green-500 rect.bar,
        .bar-wrapper.bg-green-500 rect.bar-progress { fill: #8bc34a !important; }
        .bar-wrapper.bg-yellow-500 rect.bar,
        .bar-wrapper.bg-yellow-500 rect.bar-progress { fill: #ffeb3b !important; }
        .bar-wrapper.bg-red-500 rect.bar,
        .bar-wrapper.bg-red-500 rect.bar-progress { fill: #f44336 !important; }
        .bar-wrapper.bg-purple-500 rect.bar,
        .bar-wrapper.bg-purple-500 rect.bar-progress { fill: #9c27b0 !important; }

        /* Default gray */
        .bar-wrapper:not([class*="bg-"]) rect.bar,
        .bar-wrapper:not([class*="bg-"]) rect.bar-progress { fill: #9e9e9e !important; }

        /* Popup */
        .gantt-popup {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .gantt-popup h5 {
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #0288d1;
        }
        .gantt-popup p {
            margin: 0;
            font-size: 12px;
            color: #555;
        }
    </style>
@endpush

@push('scripts')
<script>
    const ganttData = @json($ganttData);

    function initGantt(appId, data) {
        const element = document.getElementById('gantt_chart_' + appId);
        if (!element) return;

        element.innerHTML = "";

        // Filter out exempt subprocess
        const filteredTasks = data.tasks.filter(task => 
            task.name !== "Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet)"
        );

        new Gantt("#" + element.id, filteredTasks, {
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
            task_class: (task) => task.custom_class,
            custom_popup_html: function(task) {
                return `
                    <div class="gantt-popup">
                        <h5>${task.name}</h5>
                        <p>Start: ${task.start}</p>
                        <p>End: ${task.end}</p>
                        <p>Progress: ${task.progress}%</p>
                    </div>
                `;
            }
        });
    }

    function showTab(appId, event) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        document.getElementById('tab-' + appId).classList.remove('hidden');

        document.querySelectorAll('#ganttTabs a').forEach(tab => {
            tab.classList.remove('active-tab');
            tab.classList.add('inactive-tab');
        });

        event.currentTarget.classList.remove('inactive-tab');
        event.currentTarget.classList.add('active-tab');

        initGantt(appId, ganttData[appId]);
    }

    document.addEventListener("DOMContentLoaded", () => {
        const firstAppId = Object.keys(ganttData)[0];
        if (firstAppId) initGantt(firstAppId, ganttData[firstAppId]);
    });
</script>
@endpush
@endsection
