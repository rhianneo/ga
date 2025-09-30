@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-7 max-w-7xl">

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-2 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-5">

        {{-- Application Info Accordion --}}
        <div x-data="{ open: true }" class="mb-6">
            <button 
                @click="open = !open" 
                class="w-full flex justify-between items-center bg-gray-50 px-4 py-3 rounded-lg hover:bg-gray-100 transition">
                
                <div class="flex flex-col text-left">
                    <span class="text-lg font-bold text-gray-900">Application Info</span>
                    <span class="text-sm text-gray-600">Record and track the actual progress</span>
                </div>

                <svg :class="{ 'rotate-180': open }" class="w-5 h-5 text-gray-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" x-transition class="mt-2 p-3 grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-2 text-sm leading-relaxed">
                <p><span class="font-semibold">Name:</span> {{ $application->name }}</p>
                <p><span class="font-semibold">Application Type:</span> {{ $application->application_type }}</p>
                <p><span class="font-semibold">Position:</span> {{ $application->position }}</p>
                <p><span class="font-semibold">Expiry Date:</span> {{ $application->expiry_date?->format('Y-m-d') }}</p>
                <p>
                    <span class="font-semibold">Days Before Expiry:</span>
                    <span class="{{ $application->days_before_expiry <= 60 ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                        {{ intval($application->days_before_expiry) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Subprocesses Form --}}
        <form action="{{ route('actual.update', $application->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="overflow-x-auto max-h-[600px] border rounded">
                <table class="min-w-full text-sm border-collapse">
                    <thead class="bg-gray-100 text-gray-700 sticky top-0 z-10">
                        <tr class="text-center">
                            <th class="py-2 px-3 border">Major Process</th>
                            <th class="py-2 px-3 border">Subprocess</th>
                            <th class="py-2 px-3 border">Duration (Days)</th>
                            <th class="py-2 px-3 border">Actual Start Date</th>
                            <th class="py-2 px-3 border">Actual End Date</th>
                            <th class="py-2 px-3 border">Actual Duration (Days)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($groupedProcesses as $major => $subs)
                            {{-- Major Process Row --}}
                            <tr class="bg-gray-200 font-semibold">
                                <td class="py-2 px-3 text-left" colspan="6">{{ $major }}</td>
                            </tr>

                            {{-- Subprocesses --}}
                            @foreach ($subs->sortBy('order') as $process)
                                @php
                                    $existing = $actualDates->get($process->id);
                                    $isExempt = in_array($application->position, ['President & CEO', 'Vice President & COO']) 
                                                && $process->sub_process === 'Job Vacancy Proof/Published (PESO, Sunstar, & PhilJobNet)';
                                @endphp

                                <tr class="text-center even:bg-gray-50 hover:bg-gray-100 transition">
                                    <td class="py-2 px-3"></td>
                                    <td class="py-2 px-3 text-left">{{ $process->sub_process }}</td>
                                    <td class="py-2 px-3">{{ $process->duration_days }}</td>

                                    @if($isExempt)
                                        <td colspan="3" class="py-2 px-3 italic text-gray-500 text-center border">
                                            Exempted due to position
                                        </td>
                                    @else
                                        <td class="py-2 px-3 border">
                                            <input type="date" 
                                                   name="start_date[{{ $process->id }}]" 
                                                   value="{{ optional($existing)->start_date?->format('Y-m-d') ?? '' }}" 
                                                   class="w-full rounded px-2 py-1 text-sm start-date focus:ring focus:ring-blue-200">
                                        </td>
                                        <td class="py-2 px-3 border">
                                            <input type="date" 
                                                   name="end_date[{{ $process->id }}]" 
                                                   value="{{ optional($existing)->end_date?->format('Y-m-d') ?? '' }}" 
                                                   class="w-full rounded px-2 py-1 text-sm end-date focus:ring focus:ring-blue-200">
                                        </td>
                                        <td class="py-2 px-3 text-center font-medium text-gray-700 border actual-duration">
                                            {{ intval(optional($existing)->actual_duration ?? 0) }}
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Action Buttons --}}
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('actual.index') }}" 
                   class="inline-block bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded shadow font-medium">
                    Cancel
                </a>

                <button type="submit" 
                        class="inline-block bg-white hover:bg-gray-100 text-black font-semibold px-5 py-2 rounded shadow border border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Auto-calculate actual duration excluding weekends --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
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

    const rows = document.querySelectorAll('tbody tr');

    function updateDurations() {
        rows.forEach((row) => {
            const startInput = row.querySelector('.start-date');
            const endInput = row.querySelector('.end-date');
            const durationCell = row.querySelector('.actual-duration');
            if (startInput && endInput && durationCell) {
                durationCell.textContent = calculateBusinessDays(startInput.value, endInput.value);
            }
        });
    }

    document.querySelectorAll('.start-date, .end-date').forEach(input => {
        input.addEventListener('change', updateDurations);
    });

    updateDurations(); // initial run
});
</script>
@endsection
