@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Audit Logs</h1>
        <span class="text-gray-500 text-sm">Track system activities and changes</span>
    </div>

    <!-- Search & Filter -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-2">
        <form method="GET" action="{{ route('audit.index') }}" class="flex space-x-2 w-full md:w-auto">
            <input 
                type="text" 
                name="search" 
                placeholder="Search user, module, or action..." 
                value="{{ request('search') }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-full md:w-72 focus:outline-none focus:ring-2 focus:ring-blue-400 transition"
            >
            <button 
                type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Search
            </button>

            @if(request('search'))
            <a href="{{ route('audit.index') }}" class="ml-2 text-gray-500 hover:text-gray-700 text-sm self-center transition">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full text-sm border-collapse divide-y divide-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">#</th>
                    <th class="px-4 py-2 text-left font-medium">User</th>
                    <th class="px-4 py-2 text-left font-medium">Module</th>
                    <th class="px-4 py-2 text-left font-medium">Action</th>
                    <th class="px-4 py-2 text-left font-medium w-1/2">Description</th>
                    <th class="px-4 py-2 text-left font-medium">Date/Time</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2">
                        {{ ($logs->currentPage() - 1) * $logs->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-2 font-medium text-gray-800">
                        {!! highlight($log->user_name, request('search')) !!}
                    </td>
                    <td class="px-4 py-2">
                        {!! highlight($log->module, request('search')) !!}
                    </td>
                    <td class="px-4 py-2 capitalize text-blue-700 font-semibold">
                        {!! highlight($log->action, request('search')) !!}
                    </td>
                    <td class="px-4 py-2 text-gray-700 whitespace-pre-line">
                        {!! highlight($log->readable_description, request('search')) !!}
                    </td>
                    <td class="px-4 py-2 text-gray-600">
                        {{ optional($log->created_at)->timezone('Asia/Manila')->format('M d, Y h:i A') ?? 'N/A' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="px-4 py-6 text-center text-gray-500" colspan="6">
                        No audit logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $logs->appends(['search' => request('search')])->links('pagination::tailwind') }}
    </div>
</div>
@endsection

@php
/**
 * Highlight search matches in a string
 */
function highlight($text, $search) {
    if (!$search) return e($text);
    $escaped = preg_quote($search, '/');
    return preg_replace("/($escaped)/i", '<span class="bg-yellow-200 font-semibold">$1</span>', e($text));
}
@endphp
