@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-8">
        <h2 class="text-2xl font-bold mb-6">Summary of Applications</h2>
        
        <!-- Dashboard Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <div class="p-4 bg-gradient-to-r from-blue-500 to-blue-700 text-white shadow-lg rounded-lg">
                <h3 class="text-lg font-semibold text-center">Total Applications</h3>
                <p class="text-xl text-center">{{ $totalApplications }}</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-red-500 to-red-700 text-white shadow-lg rounded-lg">
                <h3 class="text-lg font-semibold text-center">Expiring Soon (within 5 months)</h3>
                <p class="text-xl text-center">{{ $expiringSoon }}</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-green-500 to-green-700 text-white shadow-lg rounded-lg">
                <h3 class="text-lg font-semibold text-center">In Progress</h3>
                <p class="text-xl text-center">{{ $inProgress }}</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-orange-500 to-orange-700 text-white shadow-lg rounded-lg">
                <h3 class="text-lg font-semibold text-center">Delayed</h3>
                <p class="text-xl text-center">{{ $delayed }}</p>
            </div>
            <div class="p-4 bg-gradient-to-r from-gray-400 to-gray-600 text-white shadow-lg rounded-lg">
                <h3 class="text-lg font-semibold text-center">Completed</h3>
                <p class="text-xl text-center">{{ $completed }}</p>
            </div>
        </div>

        <!-- Pie Charts Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
            <!-- Application Type Pie Chart -->
            <div class="p-4 bg-white shadow rounded-lg w-200"> <!-- Control width here -->
                <h3 class="text-lg font-semibold text-center">Application Type</h3>
                <canvas id="applicationTypeChart" width="300" height="300"></canvas> <!-- Set size here -->
            </div>

            <!-- Year-wise Pie Chart -->
            <div class="p-4 bg-white shadow rounded-lg w-200"> <!-- Control width here -->
                <h3 class="text-lg font-semibold text-center">Applications by Year</h3>
                <canvas id="yearChart" width="300" height="300"></canvas> <!-- Set size here -->
            </div>
        </div>


        </div>
    </div>

    <!-- Include the Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Pie chart for Application Types
        const applicationTypeData = @json($byType);
        const applicationTypeLabels = Object.keys(applicationTypeData);
        const applicationTypeCounts = Object.values(applicationTypeData);

        new Chart(document.getElementById('applicationTypeChart'), {
            type: 'pie',
            data: {
                labels: applicationTypeLabels,
                datasets: [{
                    data: applicationTypeCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });

        // Pie chart for Year-wise applications
        const yearData = @json($byYear);
        const yearLabels = Object.keys(yearData);
        const yearCounts = Object.values(yearData);

        new Chart(document.getElementById('yearChart'), {
            type: 'pie',
            data: {
                labels: yearLabels,
                datasets: [{
                    data: yearCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0'],
                    hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
