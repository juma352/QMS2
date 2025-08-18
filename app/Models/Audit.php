<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'audit_type',
        'audit_name',
        'audit_number',
        'issuing_authority',
        'auditor',
        'standard_id',
        'status',
        'date_conducted',
        'next_audit_date',
        'findings',
        'corrective_actions',
        'audit_report_path',
        'supporting_documents_paths',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_conducted' => 'date',
        'next_audit_date' => 'date',
        'supporting_documents_paths' => 'array',
    ];

    /**
     * Get the standard associated with the audit.
     */
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }

    /**
     * Get the audit checklists for this audit.
     */
    public function auditChecklists()
    {
        return $this->hasMany(AuditChecklist::class);
    }

    /**
     * Get the checklists for this audit.
     */
    public function checklists()
    {
        return $this->belongsToMany(Checklist::class, 'audit_checklists')
            ->withPivot('department_name', 'status', 'generated_by', 'generated_at', 'completed_at')
            ->withTimestamps();
    }
}