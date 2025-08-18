<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'checklist_id',
        'question_id',
        'section',
        'question_text',
        'custom_text',
        'notes',
        'status',
        'display_order',
        'question_type',
        'options',
        'help_text',
    ];

    protected $casts = [
        'options' => 'array',
        'status' => 'string',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
