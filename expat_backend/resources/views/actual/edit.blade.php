{{-- resources/views/actual/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Update Actual Progress</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Left Panel: Application Info --}}
        <div class="w-full lg:w-1/3 bg-white shadow-md rounded p-4">
            <h3 class="text-lg font-semibold mb-4">Application Info</h3>
            <p><strong>Name:</strong> {{ $application->name }}</p>
            <p><strong>Application Type:</strong> {{ $application->application_type }}</p>
            <p><strong>Position:</strong> {{ $application->position }}</p>
            <p><strong>Expiry Date:</strong> {{ $application->expiry_date?->format('Y-m-d') }}</p>
            <p><strong>Days Before Expiry:</strong> {{ $application->days_before_expiry }}</p>
        </div>

        {{-- Right Panel: Major/Subprocess Table --}}
        <div class="w-full lg:w-2/3 bg-white shadow-md rounded p-4">
            <h3 class="text-lg font-semibold mb-4">Subprocesses</h3>
            <form action="{{ route('actual.update', $application->id) }}" method="POST">
                @csrf
                @method('PUT')

                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr class="text-center">
                            <th class="py-2 px-4 border-b">Major Process</th>
                            <th class="py-2 px-4 border-b">Subprocess</th>
                            <th class="py-2 px-4 border-b">Duration (Days)</th>
                            <th class="py-2 px-4 border-b">Actual Start Date</th>
                            <th class="py-2 px-4 border-b">Actual End Date</th>
                            <th class="py-2 px-4 border-b">Actual Duration (Days)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $currentMajor = null; @endphp

                        @foreach($processes as $process)
                            @php
                                // Load existing actual dates for this application + process
                                $existing = $actualDates->has($process->id) ? $actualDates[$process->id] : null;
                            @endphp

                            <tr class="text-center">
                                {{-- Only show major process when it changes --}}
                                <td class="py-2 px-4 border-b font-semibold text-left">
                                    @if($currentMajor !== $process->major_process)
                                        {{ $process->major_process }}
                                        @php $currentMajor = $process->major_process; @endphp
                                    @endif
                                </td>

                                <td class="py-2 px-4 border-b text-left">{{ $process->sub_process }}</td>
                                <td class="py-2 px-4 border-b">{{ $process->duration_days }}</td>

                                <td class="py-2 px-4 border-b">
                                    <input type="date" 
                                           name="start_date[{{ $process->id }}]" 
                                           value="{{ optional($existing)->start_date?->format('Y-m-d') ?? '' }}" 
                                           class="w-full border border-gray-300 rounded px-2 py-1 start-date">
                                </td>

                                <td class="py-2 px-4 border-b">
                                    <input type="date" 
                                           name="end_date[{{ $process->id }}]" 
                                           value="{{ optional($existing)->end_date?->format('Y-m-d') ?? '' }}" 
                                           class="w-full border border-gray-300 rounded px-2 py-1 end-date">
                                </td>

                                <td class="py-2 px-4 border-b actual-duration text-center">
                                    {{ optional($existing)->actual_duration ?? 0 }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="flex justify-end mt-4">
                    <a href="{{ route('actual.index') }}" class="mr-4 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JS to calculate actual duration excluding weekends --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    function calculateBusinessDays(start, end) {
        if (!start || !end) return 0;
        let count = 0;
        let cur = new Date(start);
        const last = new Date(end);
        while (cur <= last) {
            const day = cur.getDay();
            if (day !== 0 && day !== 6) count++;
            cur.setDate(cur.getDate() + 1);
        }
        return count;
    }

    const startInputs = document.querySelectorAll('.start-date');
    const endInputs = document.querySelectorAll('.end-date');
    const rows = document.querySelectorAll('tbody tr');

    function updateDurations() {
        rows.forEach((row, idx) => {
            const start = startInputs[idx].value;
            const end = endInputs[idx].value;
            const durationCell = row.querySelector('.actual-duration');
            durationCell.textContent = calculateBusinessDays(start, end);
        });
    }

    startInputs.forEach(input => input.addEventListener('change', updateDurations));
    endInputs.forEach(input => input.addEventListener('change', updateDurations));

    updateDurations(); // initial calculation
});
</script>
@endsection
