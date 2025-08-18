<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditChecklistsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audits')->onDelete('cascade');
            $table->foreignId('checklist_id')->constrained('checklists')->onDelete('cascade');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->string('department_name');
            $table->enum('status', ['generated', 'in_progress', 'completed', 'archived', 'pending'])->default('generated');
            $table->timestamp('generated_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->unique(['audit_id', 'checklist_id', 'department_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_checklists');
    }
}
