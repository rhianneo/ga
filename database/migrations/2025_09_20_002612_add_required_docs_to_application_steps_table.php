<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds `required_docs` column to `application_steps` table to store step-specific document checklist.
     */
    public function up(): void
    {
        Schema::table('application_steps', function (Blueprint $table) {
            $table->json('required_docs')->nullable()->after('parallel_group')->comment('JSON array of required documents for this step');
        });
    }

    /**
     * Reverse the migrations.
     *
     * Drops `required_docs` column from `application_steps`.
     */
    public function down(): void
    {
        /*
        Schema::table('application_steps', function (Blueprint $table) {
            $table->dropColumn('required_docs');
        });
        */
    }
};
