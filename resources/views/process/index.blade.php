@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Process Management</h2>

    {{-- Tabs for Application Types --}}
    @php
        $appTypes = ['New Application', 'Renewal Application', 'Cancellation and Downgrading'];
        $activeType = request('type', 'New Application');
        $grouped = $processes->groupBy('major_process');
    @endphp

    <div class="mb-4 flex space-x-2">
        @foreach ($appTypes as $type)
            <a href="{{ route('process.index', ['type' => $type]) }}"
               class="px-4 py-2 rounded-md font-medium {{ $activeType === $type ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }}">
                {{ $type }}
            </a>
        @endforeach
    </div>

    {{-- Add New Process --}}
    <div class="mb-4">
        <a href="{{ route('process.create', ['type' => $activeType]) }}"
           class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-md">
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
    <table class="min-w-full bg-white shadow rounded-md overflow-hidden">
        <thead class="bg-gray-100">
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
                <tr class="bg-gray-200 font-semibold">
                    <td class="px-4 py-2" colspan="5">{{ $major }}</td>
                </tr>
                @foreach ($subs as $process)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2"></td>
                        <td class="px-4 py-2">{{ $process->sub_process ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $process->order }}</td>
                        <td class="px-4 py-2">{{ $process->duration_days ?? '-' }}</td>
                        <td class="px-4 py-2 space-x-2">
                            <a href="{{ route('process.edit', $process->id) }}?type={{ $activeType }}"
                               class="text-blue-500 hover:underline">Edit</a>

                            <form action="{{ route('process.destroy', $process->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this step?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td class="px-4 py-4 text-center" colspan="5">No steps yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
