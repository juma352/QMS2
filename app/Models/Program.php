<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'school_id',
        'faculty_member_id',
        'program_name',
        'program_abbr',
        'license_date',
        'appointment_date',
    ];

    /**
     * ✅ ADD THIS PROPERTY.
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'license_date' => 'datetime',
        'appointment_date' => 'datetime',
    ];

    /**
     * Defines the relationship to the School model.
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
        // Make sure this class name 'Staff' matches your actual model name
        return $this->belongsTo(Staff::class, 'faculty_member_id');
    }

    /**
     * Defines the relationship to the ProgramDocument model.
     */
    public function documents()
    {
        // Make sure this class name 'ProgramDocument' matches your actual model name
        return $this->hasMany(ProgramDocument::class);
    }
}
