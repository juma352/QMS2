<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistSubmission extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['checklist_id', 'user_id', 'subdivision_id', 'status'];

    /**
     * Defines the relationship that a Submission belongs to a single Checklist.
     * THIS IS THE MISSING METHOD.
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
     * Defines the relationship that a Submission belongs to a Subdivision.
     */
    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class);
    }
}