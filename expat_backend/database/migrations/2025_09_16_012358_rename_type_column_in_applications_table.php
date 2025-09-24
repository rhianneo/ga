<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Rename 'type' column to 'application_type'
            if (Schema::hasColumn('applications', 'type')) {
                $table->renameColumn('type', 'application_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Revert 'application_type' back to 'type' in case of rollback
            if (Schema::hasColumn('applications', 'application_type')) {
                $table->renameColumn('application_type', 'type');
            }
        });
    }
};
