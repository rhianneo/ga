@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Gantt Chart</h1>

    <!-- Tabs -->
    <ul class="flex border-b mb-4 space-x-2" id="ganttTabs">
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
            <div class="mb-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                <h2 class="font-semibold text-lg text-gray-800">
                    {{ $application->full_name }} – {{ $application->application_type }} ({{ $application->position }})
                </h2>
                <p class="text-sm text-gray-600">
                    Expiry: {{ $application->expiry_date->format('M d, Y') }} |
                    Days Before Expiry: 
                    <span class="{{ $application->days_before_expiry <= 60 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                        {{ max(0, (int) round($application->days_before_expiry)) }}
                    </span>
                </p>
            </div>
            <div id="gantt_chart_{{ $application->id }}" style="height: 400px;"></div>
        </div>
    @endforeach
</div>

<!-- Frappe Gantt JS + CSS -->
<script type="module" src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

@push('styles')
    <!-- Additional Styles -->
    <style>
        /* Tabs */
        .active-tab {
            background-color: #e0f7fa; /* Soft Blue */
            color: #0288d1; /* Blue */
            border-color: #0288d1;
        }
        .inactive-tab {
            background-color: #fff;
            color: #607d8b; /* Dark Gray */
        }

        /* Gantt Chart Custom Styling */
        /* Bar wrapper (coloring process stages with background classes) */
        .bar-wrapper.bg-blue-500 rect.bar,
        .bar-wrapper.bg-blue-500 rect.bar-progress {
            fill: #59ddd2ff !important; /* Muted Green */
        }

        .bar-wrapper.bg-green-500 rect.bar,
        .bar-wrapper.bg-green-500 rect.bar-progress {
            fill: #8bc34a !important; /* Light Green */
        }

        .bar-wrapper.bg-yellow-500 rect.bar,
        .bar-wrapper.bg-yellow-500 rect.bar-progress {
            fill: #ff903bff !important; /* Warm Yellow */
        }

        .bar-wrapper.bg-red-500 rect.bar,
        .bar-wrapper.bg-red-500 rect.bar-progress {
            fill: #f44336 !important; /* Bright Red */
        }

        .bar-wrapper.bg-purple-500 rect.bar,
        .bar-wrapper.bg-purple-500 rect.bar-progress {
            fill: #9c27b0 !important; /* Deep Purple */
        }

        /* If no bg- class is assigned, default to gray */
        .bar-wrapper:not([class*="bg-"]) rect.bar,
        .bar-wrapper:not([class*="bg-"]) rect.bar-progress {
            fill: #9e9e9e !important; /* Neutral Gray */
        }

        /* Optional: Adjust opacity for progress bar */
        .bar-wrapper.bg-green-500 rect.bar-progress {
            opacity: 0.85;
        }

        /* Customize the popup (task information) */
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
            color: #0288d1; /* Blue */
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

        // Initialize Gantt Chart
        function initGantt(appId, data) {
            const elementId = 'gantt_chart_' + appId;
            const element = document.getElementById(elementId);
            if (!element) return;

            element.innerHTML = "";

            new Gantt("#" + elementId, data.tasks, {
                view_mode: 'Day',
                date_format: 'YYYY-MM-DD',
                task_class: (task) => task.custom_class, // Ensure custom_class is applied
                custom_popup_html: function(task) {
                    return `
                        <div class="gantt-popup p-2">
                            <h5 class="font-bold">${task.name}</h5>
                            <p>Start: ${task.start}</p>
                            <p>End: ${task.end}</p>
                            <p>Progress: ${task.progress}%</p>
                        </div>
                    `;
                }
            });
        }

        // Handle Tab Switch
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

        // Initialize first tab on page load
        document.addEventListener("DOMContentLoaded", function() {
            const firstAppId = Object.keys(ganttData)[0];
            if (firstAppId) {
                initGantt(firstAppId, ganttData[firstAppId]);
            }
        });
    </script>
@endpush
@endsection
