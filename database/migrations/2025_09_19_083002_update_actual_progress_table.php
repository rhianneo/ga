<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('actual_progress', function (Blueprint $table) {
            // For New Application
            $table->date('visa_extension_start')->nullable()->after('step_id');
            $table->date('visa_extension_end')->nullable()->after('visa_extension_start');
            $table->integer('visa_extension_duration')->nullable()->after('visa_extension_end');

            $table->date('aep_start')->nullable()->after('visa_extension_duration');
            $table->date('aep_end')->nullable()->after('aep_start');
            $table->integer('aep_duration')->nullable()->after('aep_end');

            $table->date('pv_visa_start')->nullable()->after('aep_duration');
            $table->date('pv_visa_end')->nullable()->after('pv_visa_start');
            $table->integer('pv_visa_duration')->nullable()->after('pv_visa_end');

            // For Downgrading / Cancellation
            $table->date('cancellation_start')->nullable()->after('pv_visa_duration');
            $table->date('cancellation_end')->nullable()->after('cancellation_start');
            $table->integer('cancellation_duration')->nullable()->after('cancellation_end');
        });
    }

    public function down(): void
    {
        /*
        Schema::table('actual_progress', function (Blueprint $table) {
            $table->dropColumn([
                'visa_extension_start','visa_extension_end','visa_extension_duration',
                'aep_start','aep_end','aep_duration',
                'pv_visa_start','pv_visa_end','pv_visa_duration',
                'cancellation_start','cancellation_end','cancellation_duration'
            ]);
        });
        */
    }
};
