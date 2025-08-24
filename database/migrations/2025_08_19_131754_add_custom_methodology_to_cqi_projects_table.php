<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomMethodologyToCqiProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cqi_projects', function (Blueprint $table) {
            $table->string('custom_methodology')->nullable()->after('methodology');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cqi_projects', function (Blueprint $table) {
            $table->dropColumn('custom_methodology');
        });
    }
}
