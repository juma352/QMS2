<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuditIdToChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->unsignedBigInteger('audit_id')->nullable()->after('description');
            $table->foreign('audit_id')->references('id')->on('audits')->onDelete('cascade');
            $table->boolean('is_template')->default(false)->after('audit_id');
            $table->unsignedBigInteger('generated_by')->nullable()->after('is_template');
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('generated_at')->nullable()->after('generated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklists', function (Blueprint $table) {
            $table->dropForeign(['audit_id']);
            $table->dropForeign(['generated_by']);
            $table->dropColumn(['audit_id', 'is_template', 'generated_by', 'generated_at']);
        });
    }
}
