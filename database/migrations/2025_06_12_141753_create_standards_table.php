<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('standards', function (Blueprint $table) {
        $table->id();
        $table->string('standard_name');
        $table->string('standard_number')->nullable();
        $table->string('standard')->nullable();
        $table->string('clause')->nullable();
        $table->string('revision_version')->nullable();
        $table->date('date_created')->nullable();
        $table->date('date_of_issue')->nullable();
        $table->date('next_revision_date')->nullable();
        $table->date('next_date_of_issue')->nullable();
        $table->string('standard_file')->nullable();
        $table->string('other_documents')->nullable();
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
        Schema::dropIfExists('standards');
    }
}
