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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->string('prefix');
            $table->string('color',20);
            $table->integer('budget')->nullable(true);
            $table->enum('budget_type', ['fixed', 'hourly'])->nullable(true);
            $table->string('currency',20)->nullable(true);
            $table->string('description')->nullable(true);
            $table->enum('status',['archived','finished','ongoing','onhold'])->default('onhold');
            $table->string('status_color',20)->nullable(true);
            $table->foreign('client_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
