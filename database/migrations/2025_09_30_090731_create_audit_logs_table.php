<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');       // GA Staff who performed action
            $table->string('action');          // create, update, delete
            $table->string('module');          // Actual Progress, Process, Application
            $table->integer('record_id');      // affected record
            $table->text('description')->nullable(); // optional: what changed
            $table->timestamps();
        });
    }

    public function down(): void
    {
       // Schema::dropIfExists('audit_logs');
    }
};
