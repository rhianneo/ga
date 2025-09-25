<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actual_date', function (Blueprint $table) {
            $table->id();
            
            // Link to the application
            $table->unsignedBigInteger('application_id');
            
            // Link to the specific subprocess (processes table)
            $table->unsignedBigInteger('process_id');

            // Actual Start & End Dates
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // Actual Duration in business days
            $table->integer('actual_duration')->default(0);

            $table->timestamps();

            // Foreign keys
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('process_id')->references('id')->on('processes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actual_date');
    }
};
