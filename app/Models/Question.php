<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'section',
        'question_text',
        'help_text',
        'question_type',
        'options',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }
}
