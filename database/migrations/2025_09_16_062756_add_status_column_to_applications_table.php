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
        Schema::table('applications', function (Blueprint $table) {
            // Add the 'status' column if it doesn't already exist
            if (!Schema::hasColumn('applications', 'status')) {
                $table->enum('status', ['Not Started', 'In Progress', 'Completed'])->default('Not Started');
            }
        });
    }

    public function down(): void
    {
        /*
        Schema::table('applications', function (Blueprint $table) {
            // Drop the 'status' column
            $table->dropColumn('status');
        });
        */
    }

};
