<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_steps', function (Blueprint $table) {
            $table->id();
            $table->string('application_type'); // New, Renewal, Downgrading, Cancellation
            $table->string('step_name');
            $table->integer('plan_days'); // PLAN duration in days
            $table->integer('order')->default(1); // sequential order
            $table->integer('depends_on')->nullable(); // step_id this step depends on
            $table->integer('parallel_group')->nullable(); // for parallel tasks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_steps');
    }
};
