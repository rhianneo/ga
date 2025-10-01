@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Application Details: {{ $application->name }}</h1>

        @php
            $fields = [
                'Name' => $application->name,
                'Application Type' => $application->application_type,
                'Factory' => $application->factory,
                'Position' => $application->position,
                'Passport No.' => $application->passport_number ?? '-',
                'TIN' => $application->TIN ?? '-',
                'AEP No.' => $application->AEP_number ?? '-',
                'Expiry Date' => $application->expiry_date ? \Carbon\Carbon::parse($application->expiry_date)->format('F j, Y') : '-',
                'Follow-Up Date' => $application->follow_up_date ? \Carbon\Carbon::parse($application->follow_up_date)->format('F j, Y') : '-',
                'Days Before Expiry' => $application->expiry_date ? \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($application->expiry_date)) . ' days' : '-',
                'Status' => $application->status,
            ];
        @endphp

        <!-- Display Fields -->
        <div class="space-y-4 text-center">
            @foreach($fields as $label => $value)
                <div class="{{ $label === 'Days Before Expiry' && \Carbon\Carbon::parse($application->expiry_date ?? now())->diffInDays(\Carbon\Carbon::today()) < 60 ? 'text-red-500' : '' }}">
                    <strong>{{ $label }}:</strong> {{ $value }}
                </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('applications.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">Back to Applications</a>
            <button onclick="printApplication()" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600">Print</button>
        </div>
    </div>
</div>

<!-- Print Script -->
<script>
function printApplication() {
    const fields = {
        'Name': '{{ $application->name }}',
        'Application Type': '{{ $application->application_type }}',
        'Factory': '{{ $application->factory }}',
        'Position': '{{ $application->position }}',
        'Passport No.': '{{ $application->passport_number ?? "-" }}',
        'TIN': '{{ $application->TIN ?? "-" }}',
        'AEP No.': '{{ $application->AEP_number ?? "-" }}',
        'Expiry Date': '{{ $application->expiry_date ? \Carbon\Carbon::parse($application->expiry_date)->format("F j, Y") : "-" }}',
        'Follow-Up Date': '{{ $application->follow_up_date ? \Carbon\Carbon::parse($application->follow_up_date)->format("F j, Y") : "-" }}',
        'Days Before Expiry': '{{ $application->expiry_date ? \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($application->expiry_date)) : "-" }} days',
        'Status': '{{ $application->status }}'
    };

    const printWindow = window.open('', '', 'height=600,width=800');

    // Add styles
    printWindow.document.write('<html><head><title>Application Details</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; text-align: center; font-size: 12px; line-height: 1.5; }');
    printWindow.document.write('table { width: 100%; margin: 20px 0; border-collapse: collapse; font-size: 14px; }');
    printWindow.document.write('td, th { padding: 6px; border: 1px solid #ddd; text-align: left; }');
    printWindow.document.write('img { width: 120px; margin-bottom: 20px; }');
    printWindow.document.write('</style></head><body>');

    // Logo
    printWindow.document.write('<img src="{{ url('images/toyoflex.png') }}" alt="Toyoflex Logo" />');
    printWindow.document.write('<h3>Application Details for {{ $application->name }}</h3>');

    // Table of fields dynamically
    printWindow.document.write('<table>');
    for (const [label, value] of Object.entries(fields)) {
        printWindow.document.write(`<tr><th>${label}</th><td>${value}</td></tr>`);
    }
    printWindow.document.write('</table>');

    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}
</script>
@endsection
