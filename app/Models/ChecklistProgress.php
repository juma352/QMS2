<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistProgress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'checklist_submission_id',
        'current_step',
        'total_steps',
        'progress_percentage',
        'completed_steps',
        'is_draft',
        'draft_data',
        'last_saved_at',
        'estimated_completion_time',
        'started_at',
        'completed_at',
        'user_id',
        'status',
        'validation_errors',
        'notes',
    ];

    protected $casts = [
        'completed_steps' => 'array',
        'draft_data' => 'array',
        'is_draft' => 'boolean',
        'last_saved_at' => 'datetime',
        'estimated_completion_time' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'progress_percentage' => 'decimal:2',
        'validation_errors' => 'array',
    ];

    public function submission()
    {
        return $this->belongsTo(ChecklistSubmission::class, 'checklist_submission_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate progress percentage based on completed steps
     */
    public function calculateProgressPercentage()
    {
        if ($this->total_steps > 0 && is_array($this->completed_steps)) {
            $completedCount = count($this->completed_steps);
            $this->progress_percentage = round(($completedCount / $this->total_steps) * 100, 2);
            return $this->progress_percentage;
        }
        return 0;
    }

    /**
     * Mark the checklist as started
     */
    public function markAsStarted()
    {
        $this->started_at = now();
        $this->status = 'in_progress';
        $this->save();
    }

    /**
     * Mark the checklist as completed
     */
    public function markAsCompleted()
    {
        $this->completed_at = now();
        $this->status = 'completed';
        $this->calculateProgressPercentage();
        $this->save();
    }

    /**
     * Check if the checklist is overdue
     */
    public function isOverdue()
    {
        return $this->estimated_completion_time && now()->gt($this->estimated_completion_time);
    }

    /**
     * Get the time spent on the checklist
     */
    public function getTimeSpent()
    {
        if ($this->started_at) {
            $endTime = $this->completed_at ?? now();
            return $this->started_at->diff($endTime);
        }
        return null;
    }
}
