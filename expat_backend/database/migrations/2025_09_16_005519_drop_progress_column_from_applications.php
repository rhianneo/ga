<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProgressColumnFromApplications extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop the 'progress' column
            $table->dropColumn('progress');
        });
    }

    public function down(): void
    {
        // If we ever rollback, we can re-add the 'progress' column
        Schema::table('applications', function (Blueprint $table) {
            $table->string('progress')->default('Not Started')->after('days_before_expiry');
        });
    }
}
