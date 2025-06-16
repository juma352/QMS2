<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecklistItem extends Model
{
    use HasFactory;
    protected $fillable = ['checklist_id', 'section', 'question_text', 'display_order'];
}