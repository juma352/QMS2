<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_submission_id')->constrained()->onDelete('cascade');
            $table->integer('current_step')->default(1);
            $table->json('completed_steps')->nullable();
            $table->boolean('is_draft')->default(true);
            $table->json('draft_data')->nullable();
            $table->timestamp('last_saved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklist_progress');
    }
}
