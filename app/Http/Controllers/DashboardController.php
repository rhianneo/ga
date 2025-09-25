<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Total Applications: Count all applications
        $totalApplications = Application::count();

        // Expiring Soon: Applications expiring in the next 150 days
        $expiringSoon = Application::where('expiry_date', '<', now()->addDays(150))
                                ->where('expiry_date', '>=', now())
                                ->count();

        // In Progress: Applications that are in progress (status = 'In Progress')
        $inProgress = Application::where('status', 'In Progress')->count();

        // Delayed: Applications with a negative Days Before Expiry, still in progress
        $delayed = Application::where('status', 'In Progress')
                            ->where('expiry_date', '<', now())
                            ->count();

        // Completed: Applications that have the status 'Completed'
        $completed = Application::where('status', 'Completed')->count();

        // Pie Chart Data by Application Type (percentage style)
        $byType = Application::selectRaw('application_type, count(*) as count')
                            ->groupBy('application_type')
                            ->pluck('count', 'application_type')
                            ->toArray();
        $totalTypeCount = array_sum($byType);
        $byType = array_map(function ($count) use ($totalTypeCount) {
            return ($count / $totalTypeCount) * 100; // Convert to percentage
        }, $byType);

        // Pie Chart Data by Year (percentage style)
        $byYear = Application::selectRaw('YEAR(expiry_date) as year, count(*) as count')
                            ->groupBy('year')
                            ->pluck('count', 'year')
                            ->toArray();
        $totalYearCount = array_sum($byYear);
        $byYear = array_map(function ($count) use ($totalYearCount) {
            return ($count / $totalYearCount) * 100; // Convert to percentage
        }, $byYear);

        // Pass data to the view
        return view('dashboard', compact(
            'totalApplications',
            'expiringSoon',
            'inProgress',
            'delayed',
            'completed',
            'byType',
            'byYear'
        ));
    }

}
