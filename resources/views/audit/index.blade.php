@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Audit Logs</h1>

    <!-- Search -->
    <div class="flex items-center justify-between mb-4">
        <form method="GET" action="{{ route('audit.index') }}" class="flex space-x-2">
            <input 
                type="text" 
                name="search" 
                placeholder="Search user, module, or action..." 
                value="{{ request('search') }}"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
            <button 
                type="submit" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Search
            </button>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="overflow-x-auto rounded-lg shadow border border-gray-200">
        <table class="min-w-full text-sm border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="border border-gray-300 px-4 py-2 text-left">#</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">User</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Module</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Action</th>
                    <th class="border border-gray-300 px-4 py-2 text-left w-1/2">Description</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Date/Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition">
                    <td class="border border-gray-200 px-4 py-2">{{ $loop->iteration }}</td>
                    <td class="border border-gray-200 px-4 py-2 font-medium text-gray-800">{{ $log->user_name }}</td>
                    <td class="border border-gray-200 px-4 py-2">{{ $log->module }}</td>
                    <td class="border border-gray-200 px-4 py-2 capitalize text-blue-700 font-semibold">{{ $log->action }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-gray-700 whitespace-pre-line">
                        {{ $log->readable_description }}
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-gray-600">
                        {{ $log->created_at->timezone('Asia/Manila')->format('M d, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="border border-gray-200 px-4 py-6 text-center text-gray-500" colspan="6">
                        No audit logs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links('pagination::tailwind') }}
    </div>
</div>
@endsection
