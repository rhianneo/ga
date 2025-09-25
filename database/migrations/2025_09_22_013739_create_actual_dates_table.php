<?php

// database/migrations/xxxx_xx_xx_create_actual_dates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actual_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('process_id')->constrained('processes')->onDelete('cascade');
            $table->date('actual_start_date')->nullable();
            $table->date('actual_end_date')->nullable();
            $table->integer('actual_duration_days')->nullable(); // excludes weekends
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actual_dates');
    }
};
