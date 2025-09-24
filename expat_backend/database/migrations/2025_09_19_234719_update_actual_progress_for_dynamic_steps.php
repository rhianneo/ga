<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {
            // Drop all old step-specific columns
            $table->dropColumn([
                'visa_extension_start', 'visa_extension_end',
                'aep_start_1', 'aep_end_1', 'aep_start_2', 'aep_end_2',
                'aep_start_3', 'aep_end_3', 'aep_start_4', 'aep_end_4',
                'aep_start_5', 'aep_end_5', 'aep_start_6', 'aep_end_6',
                'aep_start_7', 'aep_end_7', 'aep_start_8', 'aep_end_8',
                'aep_start_9', 'aep_end_9', 'aep_start_10', 'aep_end_10',
                'pv_start_1', 'pv_end_1', 'pv_start_2', 'pv_end_2',
                'pv_start_3', 'pv_end_3', 'pv_start_4', 'pv_end_4',
                'pv_start_5', 'pv_end_5', 'pv_start_6', 'pv_end_6',
                'pv_start_7', 'pv_end_7', 'pv_start_8', 'pv_end_8',
                'cancellation_start', 'cancellation_end',
                'downgrading_start', 'downgrading_end'
            ]);

            // Add dynamic columns if not exist
            if (!Schema::hasColumn('actual_progress', 'application_step_id')) {
                $table->unsignedBigInteger('application_step_id')->after('application_id');
            }
            if (!Schema::hasColumn('actual_progress', 'start_date')) {
                $table->date('start_date')->nullable()->after('application_step_id');
            }
            if (!Schema::hasColumn('actual_progress', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('actual_progress', 'duration')) {
                $table->integer('duration')->nullable()->after('end_date');
            }
            if (!Schema::hasColumn('actual_progress', 'status')) {
                $table->enum('status', ['Not Started','In Progress','Completed'])->default('Not Started')->after('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {
            // You can optionally re-add old columns if you rollback
        });
    }
};
