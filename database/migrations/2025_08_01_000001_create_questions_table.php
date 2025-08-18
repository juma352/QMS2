<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('section');
            $table->text('question_text');
            $table->text('help_text')->nullable();
            $table->enum('question_type', ['rating', 'yes_no', 'text', 'multiple_choice'])->default('rating');
            $table->json('options')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
