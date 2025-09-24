{{-- resources/views/actual/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6">Actual Progress Entry</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center font-medium">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Application Type</th>
                    <th class="py-2 px-4 border-b">Status</th>
                    <th class="py-2 px-4 border-b">Expiry Date</th>
                    <th class="py-2 px-4 border-b">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr class="text-center">
                        <td class="py-2 px-4 border-b">{{ $application->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $application->application_type }}</td>
                        <td class="py-2 px-4 border-b">
                            <span class="px-2 py-1 rounded {{ $application->status === 'In Progress' ? 'bg-green-200 text-green-800' : '' }}">
                                {{ $application->status }}
                            </span>
                        </td>
                        <td class="py-2 px-4 border-b">{{ $application->expiry_date?->format('Y-m-d') }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('actual.edit', $application->id) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4">No applications in progress.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
