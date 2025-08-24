<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToChecklistProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('checklist_progress', function (Blueprint $table) {
            // Add total_steps column
            $table->integer('total_steps')->default(0)->after('current_step');
            
            // Add progress_percentage column
            $table->decimal('progress_percentage', 5, 2)->default(0.00)->after('total_steps');
            
            // Add estimated_completion_time column
            $table->timestamp('estimated_completion_time')->nullable()->after('last_saved_at');
            
            // Add started_at column
            $table->timestamp('started_at')->nullable()->after('estimated_completion_time');
            
            // Add completed_at column
            $table->timestamp('completed_at')->nullable()->after('started_at');
            
            // Add user_id column for tracking who last updated
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Add status column for workflow states
            
            
            // Add validation_errors column for storing validation issues
            $table->json('validation_errors')->nullable();
            
            // Add notes column for additional comments
            $table->text('notes')->nullable();
            
            // Add soft deletes
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('checklist_progress', function (Blueprint $table) {
            $table->dropColumn([
                'total_steps',
                'progress_percentage',
                'estimated_completion_time',
                'started_at',
                'completed_at',
                'user_id',
                'status',
                'validation_errors',
                'notes'
            ]);
            $table->dropSoftDeletes();
        });
    }
}
