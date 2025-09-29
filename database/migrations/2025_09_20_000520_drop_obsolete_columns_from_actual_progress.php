<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {
            // Drop foreign keys first if they exist
            if (Schema::hasColumn('actual_progress', 'employee_id')) {
                $table->dropForeign(['employee_id']);
            }
            if (Schema::hasColumn('actual_progress', 'step_id')) {
                $table->dropForeign(['step_id']);
            }

            // Drop obsolete columns
            $columnsToDrop = [
                'employee_id',
                'step_id',
                'visa_extension_duration',
                'aep_start',
                'aep_end',
                'aep_duration',
                'pv_visa_start',
                'pv_visa_end',
                'pv_visa_duration',
                'cancellation_duration',
                'actual_start_date',
                'actual_end_date',
            ];

            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('actual_progress', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('actual_progress', function (Blueprint $table) {
        //     // Re-add columns if rollback is needed
        //     $table->unsignedBigInteger('employee_id')->nullable();
        //     $table->unsignedBigInteger('step_id')->nullable();
        //     $table->integer('visa_extension_duration')->nullable();
        //     $table->date('aep_start')->nullable();
        //     $table->date('aep_end')->nullable();
        //     $table->integer('aep_duration')->nullable();
        //     $table->date('pv_visa_start')->nullable();
        //     $table->date('pv_visa_end')->nullable();
        //     $table->integer('pv_visa_duration')->nullable();
        //     $table->integer('cancellation_duration')->nullable();
        //     $table->date('actual_start_date')->nullable();
        //     $table->date('actual_end_date')->nullable();

        //     // Recreate foreign keys if needed
        //     $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
        //     $table->foreign('step_id')->references('id')->on('application_steps')->onDelete('cascade');
        // });
    }

};
