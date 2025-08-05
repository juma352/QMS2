<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->string('rating_type')->default('scale_1_5')->after('question_text');
            $table->integer('rating_value')->nullable()->after('rating_type');
            $table->text('rating_notes')->nullable()->after('rating_value');
            $table->json('rating_options')->nullable()->after('rating_notes');
        });
    }

    public function down()
    {
        Schema::table('checklist_items', function (Blueprint $table) {
            $table->dropColumn(['rating_type', 'rating_value', 'rating_notes', 'rating_options']);
        });
    }
};
