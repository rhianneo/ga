<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Expatriate Name
            $table->string('position')->nullable();
            $table->enum('factory', ['Device Factory', 'Medical Factory']);
            $table->enum('application_type', ['New Application', 'Renewal Application', 'Cancellation and Downgrading']);
            $table->string('passport_number')->nullable();
            $table->string('AEP_number')->nullable();
            $table->string('TIN')->nullable();
            $table->date('validity_date')->nullable();
            $table->date('expiry_date'); // AEP Expiry Date
            $table->enum('status', ['Not Started', 'In Progress', 'Completed'])->default('Not Started');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
