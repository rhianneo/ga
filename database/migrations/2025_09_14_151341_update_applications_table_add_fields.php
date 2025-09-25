<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Drop 'progress' column if it exists
            if (Schema::hasColumn('applications', 'progress')) {
                $table->dropColumn('progress');
            }
            // Rename 'status' to 'progress' if 'status' exists
            if (Schema::hasColumn('applications', 'status')) {
                $table->renameColumn('status', 'progress');
            }
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // Revert 'progress' back to 'status' in case of rollback
            if (Schema::hasColumn('applications', 'progress')) {
                $table->renameColumn('progress', 'status');
            }
        });
    }
};
