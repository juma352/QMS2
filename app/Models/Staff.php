<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'staff_number', 'email', 'department',
        'license_number', 'license_renewal_date',
        'license_document', 'appointment_letter', 'cv',
        'short_course_certificate', 'other_certificate'
    ];

    // Add this relationship
    public function programs()
    {
        return $this->hasMany(Program::class, 'faculty_member_id');
    }
}
