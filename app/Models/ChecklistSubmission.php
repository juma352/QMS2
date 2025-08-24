<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ChecklistSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['checklist_id', 'user_id', 'department_name', 'status', 'submitted_at'];

    /**
     * Defines the relationship that a Submission belongs to a single Checklist.
     *
     */
    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Defines the relationship that a Submission has many Answers.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(SubmissionAnswer::class);
    }
    
    /**
     * Defines the relationship that a Submission belongs to a single User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Defines the relationship that a Submission has one Progress.
     */
    public function progress(): HasOne
    {
        return $this->hasOne(ChecklistProgress::class);
    }

    /**
     * Get the status of the submission with a corresponding color.
     */
    public function getStatusWithColor(): array
    {
        $status = $this->status ?? 'draft'; // Default to draft if status is null

        switch ($status) {
            case 'submitted':
                $color = 'bg-success';
                break;
            case 'draft':
                $color = 'bg-secondary';
                break;
            default:
                $color = 'bg-info';
                break;
        }

        return [
            'status' => $status,
            'color' => $color,
        ];
    }
}
