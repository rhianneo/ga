<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('application_type'); // New Application, Renewal Application, Cancellation & Downgrading
            $table->string('major_process');
            $table->string('sub_process')->nullable();
            $table->integer('order')->default(0);
            $table->integer('duration_days')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // Schema::dropIfExists('processes');
    }
};
