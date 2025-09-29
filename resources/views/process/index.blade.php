@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Process Management</h2>

    {{-- Tabs for Application Types --}}
    @php
        $appTypes = ['New Application', 'Renewal Application', 'Cancellation and Downgrading'];
        $activeType = request('type', 'New Application');
        $grouped = $processes
            ->where('application_type', $activeType)
            ->sortBy('order')
            ->groupBy('major_process');
    @endphp

    <div class="mb-4 flex space-x-2 border-b">
        @foreach ($appTypes as $type)
            <a href="{{ route('process.index', ['type' => $type]) }}"
               class="px-4 py-2 rounded-t-md font-medium 
                      {{ $activeType === $type ? 'bg-blue-500 text-white border-b-4 border-blue-600' : 'bg-gray-200 text-gray-700' }}">
                {{ $type }}
            </a>
        @endforeach
    </div>

    {{-- Add New Process --}}
    <div class="mb-4">
        <a href="{{ route('process.create', ['type' => $activeType]) }}"
           class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded shadow-sm transition">
            + Add Step
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Nested Table --}}
    <div class="overflow-x-auto shadow rounded-md">
        <table class="min-w-full bg-white rounded-md">
            <thead class="bg-gray-100 text-gray-700 text-sm">
                <tr>
                    <th class="px-4 py-2 text-left">Major Process</th>
                    <th class="px-4 py-2 text-left">Sub Process</th>
                    <th class="px-4 py-2 text-left">Order</th>
                    <th class="px-4 py-2 text-left">Duration (days)</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($grouped as $major => $subs)
                    {{-- Major Process Row --}}
                    <tr class="bg-gray-200 font-semibold">
                        <td class="px-4 py-2" colspan="5">{{ $major }}</td>
                    </tr>

                    {{-- Sub Processes --}}
                    @foreach ($subs->sortBy('order') as $process)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2">{{ $process->sub_process ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $process->order }}</td>
                            <td class="px-4 py-2">{{ $process->duration_days ?? '-' }}</td>
                            <td class="px-4 py-2 flex space-x-2">
                                {{-- Edit Button --}}
                                <a href="{{ route('process.edit', $process->id) }}?type={{ $activeType }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded shadow-sm text-sm transition">
                                    Edit
                                </a>

                                {{-- Delete Button --}}
                                <form action="{{ route('process.destroy', $process->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this step?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-sm transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td class="px-4 py-4 text-center text-gray-500" colspan="5">No steps yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
