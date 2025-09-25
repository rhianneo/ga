@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Gantt Chart</h1>

    <!-- Tabs -->
    <ul class="flex border-b mb-4" id="ganttTabs">
        @foreach($applications as $index => $application)
            <li class="-mb-px mr-1">
                <a class="bg-white inline-block py-2 px-4 font-semibold border-l border-t border-r rounded-t 
                    {{ $index === 0 ? 'active text-blue-600 border-blue-600' : 'text-gray-500 hover:text-blue-600' }}"
                   href="javascript:void(0);"
                   onclick="showTab({{ $application->id }})">
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
                <p class="text-sm text-gray-600">
                    Expiry: {{ $application->expiry_date->format('M d, Y') }} |
                    Days Before Expiry: {{ $application->days_before_expiry }}
                </p>
            </div>
            <div id="gantt_chart_{{ $application->id }}" style="height: 400px;"></div>
        </div>
    @endforeach
</div>

<!-- Frappe Gantt JS + CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">
<script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>

<script>
    const ganttData = @json($ganttData);

    function initGantt(appId, data) {
        const elementId = 'gantt_chart_' + appId;
        const element = document.getElementById(elementId);
        if (!element) return;

        // Clear previous chart if exists
        element.innerHTML = "";

        new Gantt("#" + elementId, data.tasks, {
            view_mode: 'Day',
            date_format: 'YYYY-MM-DD',
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
    function showTab(appId) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        document.getElementById('tab-' + appId).classList.remove('hidden');

        document.querySelectorAll('#ganttTabs a').forEach(tab => {
            tab.classList.remove('active', 'text-blue-600', 'border-blue-600');
            tab.classList.add('text-gray-500');
        });

        event.target.classList.add('active', 'text-blue-600', 'border-blue-600');

        initGantt(appId, ganttData[appId]);
    }
</script>
@endsection
