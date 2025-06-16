<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submission_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('checklist_item_id')->constrained()->onDelete('cascade');
            $table->integer('rating');
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('submission_answers');
    }
}
