<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditChecklist extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_id',
        'checklist_id',
        'generated_by',
        'department_name',
        'status',
        'generated_at',
        'completed_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(Checklist::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
