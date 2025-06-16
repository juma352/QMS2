<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('programs', function (Blueprint $table) {
        $table->id();
        $table->string('program_name');
        $table->string('program_abbr')->nullable();
        $table->string('subdivision')->nullable();
        $table->string('faculty_member')->nullable();
        $table->string('role')->nullable();
        $table->date('license_renewal_date')->nullable();
        $table->date('next_approval_date')->nullable();
        $table->string('license_document')->nullable();
        $table->string('approval_document')->nullable();
        $table->string('certificate')->nullable();
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
        Schema::dropIfExists('programs');
    }
}
