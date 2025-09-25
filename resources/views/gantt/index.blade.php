@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Gantt Chart</h1>

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
            <div class="mb-4 p-4 bg-gray-100 rounded shadow">
                <h2 class="font-bold text-lg">
                    {{ $application->full_name }} – {{ $application->application_type }} ({{ $application->position }})
                </h2>
                <p class="text-sm">
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

<!-- Frappe Gantt JS + CSS - Use ES module type for proper import -->
<script type="module" src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

<style>
    /* Tabs styling */
    .active-tab {
        background-color: #eff6ff; /* light blue */
        color: #1d4ed8; /* blue */
        border-color: #1d4ed8;
    }
    .inactive-tab {
        background-color: #fff;
        color: #6b7280; /* gray */
    }

    /* Gantt Bar Colors */
    .bg-blue-500 { fill: #3b82f6 !important; }   /* Visa Extension - Blue */
    .bg-green-500 { fill: #22c55e !important; }  /* AEP Application - Green */
    .bg-yellow-500 { fill: #f59e0b !important; } /* PV VISA Application - Yellow */
    .bg-red-500 { fill: #ef4444 !important; }    /* Cancellation of PEZA Visa - Red */
    .bg-purple-500 { fill: #8b5cf6 !important; } /* Downgrading of PEZA Visa - Purple */
</style>

<script>
    const ganttData = @json($ganttData);

    function initGantt(appId, data) {
        const elementId = 'gantt_chart_' + appId;
        const element = document.getElementById(elementId);
        if (!element) return;

        element.innerHTML = "";

        new Gantt("#" + elementId, data.tasks, {
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
            custom_class: task => {
                return task.custom_class; // Use the color class dynamically
            },
            custom_popup_html: function(task) {
                return `
                    <div class="p-2">
                        <h5 class="font-bold">${task.name}</h5>
                        <p>Start: ${task.start}</p>
                        <p>End: ${task.end}</p>
                        <p>Progress: ${task.progress}%</p>
                    </div>
                `;
            }
        });
    }

    // Initialize first tab chart
    document.addEventListener("DOMContentLoaded", function() {
        const firstAppId = Object.keys(ganttData)[0];
        if (firstAppId) {
            initGantt(firstAppId, ganttData[firstAppId]);
        }
    });

    // Tab switching
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
</script>
@endsection
