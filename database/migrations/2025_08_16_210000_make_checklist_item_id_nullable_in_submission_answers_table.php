<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeChecklistItemIdNullableInSubmissionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('submission_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('checklist_item_id')->nullable()->change();
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
            $table->unsignedBigInteger('checklist_item_id')->nullable(false)->change();
        });
    }
}
