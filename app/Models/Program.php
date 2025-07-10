<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'faculty_member_id',
        'program_name',
        'program_abbr',
        'role',
        'license_renewal_date',
        'next_approval_date',
        'license_document',
        'approval_document',
        'certificate',
        'other_documents'
    ];

    /**
     * Defines the relationship to the School model.
     * This is the missing method that caused the error.
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Defines the relationship to the Staff model for the faculty member.
     */
    public function facultyMember()
    {
        return $this->belongsTo(Staff::class, 'faculty_member_id');
    }
}
