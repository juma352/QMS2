<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingValuesToAuditChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_checklists', function (Blueprint $table) {
            // Add status field to track checklist completion status
            $table->enum('status', ['pending', 'in_progress', 'completed', 'overdue'])
                  ->default('pending')
                  ->after('generated_at');
            
            // Add completed_at timestamp
            $table->timestamp('completed_at')->nullable()->after('status');
            
            // Add score field for numeric rating
            $table->decimal('score', 5, 2)->nullable()->after('completed_at');
            
            // Add notes field for additional comments
            $table->text('notes')->nullable()->after('score');
            
            // Add assigned_to field for user responsible
            $table->unsignedBigInteger('assigned_to')->nullable()->after('notes');
            
            // Add due_date field for deadline
            $table->date('due_date')->nullable()->after('assigned_to');
            
            // Foreign key constraint for assigned_to
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Add indexes for better performance
            $table->index('status');
            $table->index('assigned_to');
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_checklists', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['assigned_to']);
            
            // Drop all added columns
            $table->dropColumn([
                'status',
                'completed_at',
                'score',
                'notes',
                'assigned_to',
                'due_date'
            ]);
        });
    }
}
