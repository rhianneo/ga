@extends('layouts.app')

@section('content')
<h2 class="text-xl font-semibold text-center text-gray-700 mb-4">
    Employee Information
</h2>

<!-- Success Message -->
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

<!-- Filter Section -->
<form method="GET" action="{{ route('applications.index') }}" class="flex flex-wrap justify-center gap-4 mb-6">
    <!-- Application Type Filter -->
    <select name="type" class="border rounded px-8 py-2">
        <option value="">All Applications</option>
        <option value="New Application" {{ request('type') == 'New Application' ? 'selected' : '' }}>New Application</option>
        <option value="Renewal Application" {{ request('type') == 'Renewal Application' ? 'selected' : '' }}>Renewal Application</option>
        <option value="Downgrade/Cancellation" {{ request('type') == 'Downgrade/Cancellation' ? 'selected' : '' }}>Downgrade/Cancellation</option>
    </select>

    <!-- Factory Filter -->
    <select name="factory" class="border rounded px-8 py-2">
        <option value="">All Factories</option>
        <option value="Device Factory" {{ request('factory') == 'Device Factory' ? 'selected' : '' }}>Device Factory</option>
        <option value="Medical Factory" {{ request('factory') == 'Medical Factory' ? 'selected' : '' }}>Medical Factory</option>
    </select>

    <!-- Year Filter -->
    <select name="year" class="border rounded px-8 py-2">
        <option value="">All Years</option>
        @foreach($years as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
        @endforeach
    </select>

    <!-- Filter Button -->
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Filter
    </button>

    <!-- Print Button -->
    <button type="button" onclick="window.print()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 ml-2">
        Print
    </button>

    <!-- Create Application Button -->
    <a href="{{ route('applications.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 ml-2">
        Create New Application
    </a>
</form>

<!-- Application Table -->
<div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded shadow text-center" id="application-table">
        <thead class="bg-lightblue-300 text-white">
            <tr>
                <th class="py-2 px-4">No.</th>
                <th class="py-2 px-4">Name</th>
                <th class="py-2 px-4">Application Type</th>
                <th class="py-2 px-4">Factory</th>
                <th class="py-2 px-4">Position</th>
                <th class="py-2 px-4">AEP Number</th>
                <th class="py-2 px-4">Expiry Date</th>
                <th class="py-2 px-4">Days Before Expiry</th>
                <th class="py-2 px-4">Status</th>
                <!-- Actions Column (Hidden on Print) -->
                <th class="py-2 px-4 print:hidden">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $index => $app)
                <tr class="border-b">
                    <td class="py-2 px-4">
                        {{ ($applications->currentPage() - 1) * $applications->perPage() + $index + 1 }}
                    </td>
                    <td class="py-2 px-4">{{ $app->name }}</td>
                    <td class="py-2 px-4">{{ $app->application_type }}</td>
                    <td class="py-2 px-4">{{ $app->factory }}</td>
                    <td class="py-2 px-4">{{ $app->position }}</td>
                    <td class="py-2 px-4">{{ $app->AEP_number ?? '-' }}</td>
                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($app->expiry_date)->format('Y-m-d') }}</td>
                    <td class="py-2 px-4 {{ abs(intval(\Carbon\Carbon::parse($app->expiry_date)->diffInDays(now()))) < 60 ? 'text-red-500' : '' }}">
                        {{ abs(intval(\Carbon\Carbon::parse($app->expiry_date)->diffInDays(now()))) }} days
                    </td>
                    <td class="py-2 px-4">{{ $app->status }}</td>
                    <!-- Actions Column (Visible only for non-Print views) -->
                    <td class="py-2 px-4 flex justify-center gap-2 print:hidden">
                        <a href="{{ route('applications.show', $app->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</a>
                        <a href="{{ route('applications.edit', $app->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">Edit</a>
                        <form action="{{ route('applications.destroy', $app->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="py-4">No applications found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination Links -->
    <div class="flex justify-center mt-4">
        {{ $applications->links() }} <!-- Pagination links -->
    </div>
</div>

@endsection

@section('scripts')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #application-table, #application-table * {
                visibility: visible;
            }
            #application-table {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            /* Center the Toyoflex Logo */
            .print-header {
                text-align: center;
                margin-bottom: 20px;
                font-size: 12px;
            }

            .print-header img {
                width: 150px;
                height: auto;
            }

            /* Hide the actions column in print */
            .print:hidden {
                display: none;
            }

            /* Smaller font size for print */
            #application-table th, #application-table td {
                font-size: 12px;
                padding: 6px 8px;
            }

            /* Add print header */
            .print-header h2 {
                font-size: 18px;
                margin-top: 10px;
            }
        }
    </style>
    <script>
        window.onbeforeprint = function () {
            // Add the print header with logo and title
            const header = document.createElement('div');
            header.className = 'print-header';
            header.innerHTML = '<img src="{{ asset("images/toyoflex.png") }}" alt="Toyoflex Logo"><h2>AEP and PV Visa Application Details</h2>';
            document.body.insertBefore(header, document.body.firstChild);
        };
    </script>
@endsection
