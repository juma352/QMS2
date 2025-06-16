<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checklist extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'description'];

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('display_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ChecklistSubmission::class);
    }
}