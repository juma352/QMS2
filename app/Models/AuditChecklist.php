<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditChecklist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'audit_checklists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'audit_id',
        'checklist_id',
        'generated_by',
        'generated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'generated_at' => 'datetime',
    ];

    /**
     * Get the audit that this checklist belongs to.
     */
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    /**
     * Get the checklist associated with this audit.
     */
    public function checklist()
    {
        return $this->belongsTo(Checklist::class);
    }

    /**
     * Get the user who generated this checklist.
     */
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
