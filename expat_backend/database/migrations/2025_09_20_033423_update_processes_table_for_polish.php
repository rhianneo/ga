<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            // Change sub_process to text for long descriptions
            $table->text('sub_process')->nullable()->change();

            // Start order from 1
            $table->integer('order')->default(1)->change();

            // Default duration to 1 day
            $table->integer('duration_days')->default(1)->change();

            // Add index for faster filtering
            $table->index(['application_type', 'major_process']);
        });
    }

    public function down(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            $table->string('sub_process')->nullable()->change();
            $table->integer('order')->default(0)->change();
            $table->integer('duration_days')->nullable()->change();
            $table->dropIndex(['application_type', 'major_process']);
        });
    }
};
