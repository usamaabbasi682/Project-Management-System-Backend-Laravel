<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->string('title');
            $table->text('description')->nullable(true);
            $table->date('due_date')->nullable(true);
            $table->string('estimated_time',20)->nullable(true);
            $table->string('estimated_time_type',20)->nullable(true);
            $table->enum('time_type', ['hours', 'days'])->default('hours');
            $table->enum('priority', ['highest', 'high', 'medium' ,'low', 'lowest'])->default('medium');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');  
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
