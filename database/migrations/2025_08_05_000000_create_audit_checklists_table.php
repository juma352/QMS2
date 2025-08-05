<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_id');
            $table->unsignedBigInteger('checklist_id');
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('audit_id')
                  ->references('id')
                  ->on('audits')
                  ->onDelete('cascade');

            $table->foreign('checklist_id')
                  ->references('id')
                  ->on('checklists')
                  ->onDelete('cascade');

            $table->foreign('generated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // Indexes for better performance
            $table->index('audit_id');
            $table->index('checklist_id');
            $table->index('generated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('audit_checklists');
    }
}
