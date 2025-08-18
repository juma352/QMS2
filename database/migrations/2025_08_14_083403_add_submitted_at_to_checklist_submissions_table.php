<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubmittedAtToChecklistSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table('checklist_submissions', function (Blueprint $table) {
            $table->timestamp('submitted_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('checklist_submissions', function (Blueprint $table) {
            $table->dropColumn('submitted_at');
        });
    }
}
