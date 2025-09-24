<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    public function run()
    {
        // Renewal applications
        $applications = [
            [
                'email' => 'yoh.nobuta@asahi-intecc.com',
                'application_type' => 'Renewal Application',
                'progress' => 'In Progress',
                'position' => 'President & CEO',
                'follow_up_date' => '2025-08-14',
                'expiry_date' => '2025-11-14',
                'aep_number' => 'RO7-2023-09-000735',
                'tin' => '607-187-755',
            ],
            [
                'email' => 'daisuke.nakayama@asahi-intecc.com',
                'application_type' => 'Renewal Application',
                'progress' => 'Not Started',
                'position' => 'Production Division Technical Manager',
                'factory' => 'Device Factory',
                'follow_up_date' => '2025-10-29',
                'expiry_date' => '2026-01-29',
                'aep_number' => 'R07202001470',
                'tin' => '359-682-575',
            ],
            [
                'email' => 'tadashi.doi@asahi-intecc.com',
                'application_type' => 'Renewal Application',
                'progress' => 'Not Started',
                'position' => 'Purchasing & Logistics Division Technical Manager',
                'factory' => 'Device Factory',
                'follow_up_date' => '2025-12-02',
                'expiry_date' => '2026-03-04',
                'aep_number' => 'R07-2024-01-000058',
                'tin' => '541-713-085',
            ],
            [
                'email' => 'yumiko.morrison@asahi-intecc.com',
                'application_type' => 'Renewal Application',
                'progress' => 'Not Started',
                'position' => 'Interpreter / Translator',
                'factory' => 'Medical Factory',
                'follow_up_date' => '2025-12-11',
                'expiry_date' => '2026-03-13',
                'aep_number' => 'RO7201902419',
                'tin' => '238-018-328',
            ],
            // Additional renewal applications...
        ];

        // Insert renewal applications with dynamic "days_before_expiry" calculation
        foreach ($applications as $application) {
            $expiryDate = Carbon::parse($application['expiry_date']);
            $daysBeforeExpiry = Carbon::now()->diffInDays($expiryDate); // Automatically calculate the days before expiry

            Application::create([
                'email' => $application['email'],
                'application_type' => $application['application_type'],
                'progress' => $application['progress'],
                'position' => $application['position'],
                'factory' => $application['factory'] ?? null, // Optional field for factory
                'follow_up_date' => $application['follow_up_date'],
                'expiry_date' => $application['expiry_date'],
                'aep_number' => $application['aep_number'],
                'tin' => $application['tin'],
                'days_before_expiry' => $daysBeforeExpiry,
            ]);
        }

        // New applications
        $newApplications = [
            [
                'email' => 'naoyuki.iwaita@asahi-intecc.com',
                'application_type' => 'New Application',
                'progress' => 'In Progress',
                'position' => 'Production Division Technical Manager',
                'factory' => 'Medical Factory',
                'follow_up_date' => '2027-05-26',
                'expiry_date' => '2027-08-26',
                'aep_number' => 'R07-2025-08-000556',
                'tin' => '680-097-959',
            ],
            [
                'email' => 'ryoya.nomura@asahi-intecc.com',
                'application_type' => 'New Application',
                'progress' => 'In Progress',
                'position' => 'Machine Design Section Technical Manager',
                'factory' => 'Device Factory',
                'follow_up_date' => '2027-05-10',
                'expiry_date' => '2027-08-10',
                'aep_number' => 'R07-2025-08-000494',
                'tin' => '678-651-107',
            ],
            // Additional new applications...
        ];

        // Insert new applications with dynamic "days_before_expiry" calculation
        foreach ($newApplications as $application) {
            $expiryDate = Carbon::parse($application['expiry_date']);
            $daysBeforeExpiry = Carbon::now()->diffInDays($expiryDate); // Automatically calculate the days before expiry

            Application::create([
                'email' => $application['email'],
                'application_type' => $application['application_type'],
                'progress' => $application['progress'],
                'position' => $application['position'],
                'factory' => $application['factory'] ?? null, // Optional field for factory
                'follow_up_date' => $application['follow_up_date'],
                'expiry_date' => $application['expiry_date'],
                'aep_number' => $application['aep_number'],
                'tin' => $application['tin'],
                'days_before_expiry' => $daysBeforeExpiry,
            ]);
        }
    }
}
