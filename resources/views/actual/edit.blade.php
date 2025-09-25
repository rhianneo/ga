{{-- resources/views/actual/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8 max-w-5xl">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">

        {{-- Application Info Accordion --}}
        <div x-data="{ open: true }" class="mb-8">
            <button 
                @click="open = !open" 
                class="w-full flex justify-between items-center bg-gray-50 px-4 py-3 rounded-lg shadow-sm hover:bg-gray-100 transition">
                <div class="flex flex-col text-left">
                    <span class="text-lg font-bold text-gray-900">Application Info</span>
                    <span class="text-base text-gray-600">Record and track the actual progress</span>
                </div>

                <svg :class="{'rotate-180': open}" class="w-6 h-6 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition 
                class="mt-3 bg-white border rounded-lg shadow-sm p-4 text-sm grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3 leading-relaxed">
                <p><span class="font-semibold">Name:</span> <span class="text-gray-800">{{ $application->name }}</span></p>
                <p><span class="font-semibold">Application Type:</span> <span class="text-gray-800">{{ $application->application_type }}</span></p>
                <p><span class="font-semibold">Position:</span> <span class="text-gray-800">{{ $application->position }}</span></p>
                <p><span class="font-semibold">Expiry Date:</span> <span class="text-gray-800">{{ $application->expiry_date?->format('Y-m-d') }}</span></p>
                <p>
                    <span class="font-semibold">Days Before Expiry:</span>
                    <span class="{{ $application->days_before_expiry <= 60 ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                        {{ intval($application->days_before_expiry) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Subprocesses Table --}}
        <form action="{{ route('actual.update', $application->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto max-h-[500px] border rounded-lg shadow-sm">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10 shadow-sm">
                        <tr class="text-center">
                            <th class="py-2 px-3 border-b">Major Process</th>
                            <th class="py-2 px-3 border-b">Subprocess</th>
                            <th class="py-2 px-3 border-b">Duration (Days)</th>
                            <th class="py-2 px-3 border-b">Actual Start Date</th>
                            <th class="py-2 px-3 border-b">Actual End Date</th>
                            <th class="py-2 px-3 border-b">Actual Duration (Days)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $currentMajor = null; @endphp

                        @foreach($processes as $process)
                            @php
                                $existing = $actualDates->has($process->id) ? $actualDates[$process->id] : null;
                            @endphp

                            <tr class="text-center even:bg-gray-50 hover:bg-gray-100 transition">
                                {{-- Show major process only when it changes --}}
                                <td class="py-2 px-3 border-b font-semibold text-left align-top">
                                    @if($currentMajor !== $process->major_process)
                                        {{ $process->major_process }}
                                        @php $currentMajor = $process->major_process; @endphp
                                    @endif
                                </td>

                                <td class="py-2 px-3 border-b text-left">{{ $process->sub_process }}</td>
                                <td class="py-2 px-3 border-b">{{ $process->duration_days }}</td>

                                <td class="py-2 px-3 border-b">
                                    <input type="date" 
                                           name="start_date[{{ $process->id }}]" 
                                           value="{{ optional($existing)->start_date?->format('Y-m-d') ?? '' }}" 
                                           class="w-full border border-gray-300 rounded px-2 py-1 start-date text-sm focus:ring focus:ring-blue-200">
                                </td>

                                <td class="py-2 px-3 border-b">
                                    <input type="date" 
                                           name="end_date[{{ $process->id }}]" 
                                           value="{{ optional($existing)->end_date?->format('Y-m-d') ?? '' }}" 
                                           class="w-full border border-gray-300 rounded px-2 py-1 end-date text-sm focus:ring focus:ring-blue-200">
                                </td>

                                <td class="py-2 px-3 border-b actual-duration text-center font-medium text-gray-700">
                                    {{ intval(optional($existing)->actual_duration ?? 0) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end mt-6 space-x-4">
                {{-- Cancel Button --}}
                <a href="{{ route('actual.index') }}" 
                class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow font-medium">
                    Cancel
                </a>

                {{-- Update Button --}}
                <button type="submit" 
                        class="inline-block bg-white hover:bg-gray-100 text-black font-semibold px-5 py-2 rounded shadow border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Update
                </button>
            </div>

        </form>
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
