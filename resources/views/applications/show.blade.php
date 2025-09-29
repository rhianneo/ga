@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-lg bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-4 text-center">Application Details: {{ $application->name }}</h1>

        <div class="space-y-4 text-center">
            <!-- Displaying Application Information -->
            <div>
                <strong>Name:</strong> {{ $application->name }}
            </div>
            <div>
                <strong>Application Type:</strong> {{ $application->application_type }}
            </div>
            <div>
                <strong>Factory:</strong> {{ $application->factory }}
            </div>
            <div>
                <strong>Position:</strong> {{ $application->position }}
            </div>
            <div>
                <strong>Passport No.:</strong> {{ $application->passport_number }}
            </div>
            <div>
                <strong>TIN:</strong> {{ $application->TIN }}
            </div>
            <div>
                <strong>AEP No.:</strong> {{ $application->AEP_number }}
            </div>
            <div>
                <strong>Expiry Date:</strong> {{ $application->expiry_date->format('F j, Y') }}
            </div>
            <div>
                <strong>Follow-Up Date:</strong> {{ $follow_up_date->format('F j, Y') }}
            </div>

            <!-- Days Before Expiry -->
            <div class="{{ \Carbon\Carbon::today()->diffInDays($application->expiry_date) < 60 ? 'text-red-500' : '' }}">
                <strong>Days Before Expiry:</strong> 
                {{ floor(\Carbon\Carbon::today()->diffInDays($application->expiry_date)) }} days
            </div>


            <div>
                <strong>Status:</strong> {{ $application->status }}
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <!-- Back Button -->
            <a href="{{ route('applications.index') }}" class="bg-gray-600 text-white px-6 py-2 rounded-md hover:bg-gray-700">Back to Applications</a>

            <!-- Print Button -->
            <button onclick="printApplication()" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600">Print</button>
        </div>
    </div>
</div>

<!-- Print Layout Template -->
<script>
    function printApplication() {
        var printWindow = window.open('', '', 'height=600,width=800');

        // Add styles for print layout
        printWindow.document.write('<html><head><title>Application Details</title>');
        printWindow.document.write('<style>body { font-family: Arial, sans-serif; margin: 0; padding: 0; text-align: center; font-size: 12px; line-height: 1.5; }');
        printWindow.document.write('table { width: 100%; margin: 20px 0; border-collapse: collapse; font-size: 14px; }');
        printWindow.document.write('td, th { padding: 6px; border: 1px solid #ddd; text-align: left; }');
        printWindow.document.write('img { width: 120px; margin-bottom: 20px; }');
        printWindow.document.write('</style></head><body>');

        // Company Logo (explicit URL path)
        printWindow.document.write('<img src="{{ url('images/toyoflex.png') }}" alt="Toyoflex Logo" />');
        
        // Application Title and Details
        printWindow.document.write('<h3>Application Details for ' + '{{ $application->name }}' + '</h3>');
        
        // Application details in a table format
        printWindow.document.write('<table>');
        printWindow.document.write('<tr><th>Name</th><td>' + '{{ $application->name }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Application Type</th><td>' + '{{ $application->application_type }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Factory</th><td>' + '{{ $application->factory }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Position</th><td>' + '{{ $application->position }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Passport No.</th><td>' + '{{ $application->passport_number }}' + '</td></tr>');
        printWindow.document.write('<tr><th>TIN</th><td>' + '{{ $application->TIN }}' + '</td></tr>');
        printWindow.document.write('<tr><th>AEP No.</th><td>' + '{{ $application->AEP_number }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Expiry Date</th><td>' + '{{ $application->expiry_date->format("F j, Y") }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Follow-Up Date</th><td>' + '{{ $follow_up_date->format("F j, Y") }}' + '</td></tr>');
        printWindow.document.write('<tr><th>Days Before Expiry</th><td>' + '{{ floor(\Carbon\Carbon::now()->diffInDays($application->expiry_date)) }}' + ' days</td></tr>');
        printWindow.document.write('<tr><th>Status</th><td>' + '{{ $application->status }}' + '</td></tr>');
        printWindow.document.write('</table>');
        
        // Closing the print window and directly trigger the print dialog
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Automatically trigger the print dialog once the content is ready
        printWindow.print();
    }
    

</script>

@endsection
