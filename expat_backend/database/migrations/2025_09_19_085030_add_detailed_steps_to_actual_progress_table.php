<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {

            // Visa Extension
            if (!Schema::hasColumn('actual_progress', 'visa_extension_start')) {
                $table->date('visa_extension_start')->nullable();
            }
            if (!Schema::hasColumn('actual_progress', 'visa_extension_end')) {
                $table->date('visa_extension_end')->nullable();
            }

            // AEP Application Steps 1-10
            for ($i = 1; $i <= 10; $i++) {
                if (!Schema::hasColumn('actual_progress', "aep_start_$i")) {
                    $table->date("aep_start_$i")->nullable();
                }
                if (!Schema::hasColumn('actual_progress', "aep_end_$i")) {
                    $table->date("aep_end_$i")->nullable();
                }
            }

            // PV VISA Application Steps 1-8
            for ($i = 1; $i <= 8; $i++) {
                if (!Schema::hasColumn('actual_progress', "pv_start_$i")) {
                    $table->date("pv_start_$i")->nullable();
                }
                if (!Schema::hasColumn('actual_progress', "pv_end_$i")) {
                    $table->date("pv_end_$i")->nullable();
                }
            }

            // Cancellation & Downgrading
            if (!Schema::hasColumn('actual_progress', 'cancellation_start')) {
                $table->date('cancellation_start')->nullable();
            }
            if (!Schema::hasColumn('actual_progress', 'cancellation_end')) {
                $table->date('cancellation_end')->nullable();
            }
            if (!Schema::hasColumn('actual_progress', 'downgrading_start')) {
                $table->date('downgrading_start')->nullable();
            }
            if (!Schema::hasColumn('actual_progress', 'downgrading_end')) {
                $table->date('downgrading_end')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {
            // Only drop if they exist
            foreach ([
                'visa_extension_start', 'visa_extension_end',
                'cancellation_start', 'cancellation_end',
                'downgrading_start', 'downgrading_end'
            ] as $column) {
                if (Schema::hasColumn('actual_progress', $column)) {
                    $table->dropColumn($column);
                }
            }

            for ($i = 1; $i <= 10; $i++) {
                if (Schema::hasColumn('actual_progress', "aep_start_$i")) {
                    $table->dropColumn("aep_start_$i");
                }
                if (Schema::hasColumn('actual_progress', "aep_end_$i")) {
                    $table->dropColumn("aep_end_$i");
                }
            }

            for ($i = 1; $i <= 8; $i++) {
                if (Schema::hasColumn('actual_progress', "pv_start_$i")) {
                    $table->dropColumn("pv_start_$i");
                }
                if (Schema::hasColumn('actual_progress', "pv_end_$i")) {
                    $table->dropColumn("pv_end_$i");
                }
            }
        });
    }
};
