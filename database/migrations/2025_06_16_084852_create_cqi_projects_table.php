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
        Schema::create('cqi_projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->text('description')->nullable();
            $table->text('mission')->nullable();
            $table->string('project_leader')->nullable();
            $table->string('methodology');
            $table->text('problem_statement')->nullable();
            $table->text('smart_goals')->nullable();
            $table->text('metrics_to_track')->nullable();
            $table->text('data_collection_method')->nullable();
            $table->unsignedInteger('initial_progress')->default(0);
            $table->string('status')->default('Not Started');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cqi_projects');
    }
};