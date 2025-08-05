<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title', 
        'slug', 
        'description', 
        'audit_id',
        'is_template',
        'generated_by',
        'generated_at'
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'is_template' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ChecklistItem::class)->orderBy('display_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(ChecklistSubmission::class);
    }

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function auditChecklists(): HasMany
    {
        return $this->hasMany(AuditChecklist::class);
    }
}
