<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id',
        'section',
        'question_text',
        'display_order',
        'is_placeholder',
        'rating_type',
        'rating_value',
        'rating_notes',
        'rating_options',
    ];

    protected $casts = [
        'rating_options' => 'array',
        'rating_value' => 'integer',
        'is_placeholder' => 'boolean',
    ];

    public const RATING_TYPES = [
        'scale_1_5' => '1-5 Scale',
        'scale_1_10' => '1-10 Scale',
        'yes_no' => 'Yes/No',
        'pass_fail' => 'Pass/Fail',
        'custom' => 'Custom Options',
    ];

    public const RATING_SCALES = [
        'scale_1_5' => [
            1 => '1 - Poor',
            2 => '2 - Fair',
            3 => '3 - Good',
            4 => '4 - Very Good',
            5 => '5 - Excellent',
        ],
        'scale_1_10' => [
            1 => '1 - Very Poor',
            2 => '2 - Poor',
            3 => '3 - Fair',
            4 => '4 - Below Average',
            5 => '5 - Average',
            6 => '6 - Above Average',
            7 => '7 - Good',
            8 => '8 - Very Good',
            9 => '9 - Excellent',
            10 => '10 - Outstanding',
        ],
        'yes_no' => [
            1 => 'Yes',
            0 => 'No',
        ],
        'pass_fail' => [
            1 => 'Pass',
            0 => 'Fail',
        ],
    ];

    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    public function getRatingLabel()
    {
        $scale = self::RATING_SCALES[$this->rating_type] ?? null;
        if ($scale && isset($scale[$this->rating_value])) {
            return $scale[$this->rating_value];
        }
        
        if ($this->rating_type === 'custom' && $this->rating_options) {
            return $this->rating_options[$this->rating_value] ?? $this->rating_value;
        }
        
        return $this->rating_value;
    }

    public function getRatingOptions()
    {
        if ($this->rating_type === 'custom' && $this->rating_options) {
            return $this->rating_options;
        }
        
        return self::RATING_SCALES[$this->rating_type] ?? [];
    }
}
