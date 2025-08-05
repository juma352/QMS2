<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceSubdivisionIdWithDepartmentNameInChecklistSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklist_submissions', function (Blueprint $table) {
            // Add department_name column
            $table->string('department_name')->nullable()->after('user_id');
            
            // Remove subdivision_id foreign key constraint and column
            $table->dropForeign(['subdivision_id']);
            $table->dropColumn('subdivision_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklist_submissions', function (Blueprint $table) {
            // Add back subdivision_id column and foreign key constraint
            $table->foreignId('subdivision_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
            
            // Remove department_name column
            $table->dropColumn('department_name');
        });
    }
}