<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
{
    Schema::create('staff', function (Blueprint $table) {
        $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('staff_number')->unique();
        $table->string('email')->unique();
        $table->string('department')->nullable();
        $table->string('license_number')->nullable();
        $table->date('license_renewal_date')->nullable();
        $table->string('license_document')->nullable();
        $table->string('appointment_letter')->nullable();
        $table->string('cv')->nullable();
        $table->string('short_course_certificate')->nullable();
        $table->string('other_certificate')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
