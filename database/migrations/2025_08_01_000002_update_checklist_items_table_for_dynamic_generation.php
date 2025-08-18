<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateChecklistItemsTableForDynamicGeneration extends Migration
{
    public function up()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->foreignId('question_id')->nullable()->constrained('questions')->onDelete('cascade');
            $table->text('custom_text')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'in_progress', 'not_applicable'])->default('pending');
            $table->string('question_type')->default('rating');
            $table->json('options')->nullable();
            $table->text('help_text')->nullable();
        });
    }

    public function down()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropColumn(['question_id', 'custom_text', 'notes', 'status', 'question_type', 'options', 'help_text']);
        });
    }
}
