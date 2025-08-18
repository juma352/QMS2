<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionKeyToSubmissionAnswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_answers', function (Blueprint $table) {
            $table->string('question_key')->nullable()->after('checklist_item_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submission_answers', function (Blueprint $table) {
            $table->dropColumn('question_key');
        });
    }
}
