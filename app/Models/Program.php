<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
    'program_name', 'program_abbr', 'subdivision', 'faculty_member', 'role',
    'license_renewal_date', 'next_approval_date',
    'license_document', 'approval_document', 'certificate', 'other_documents'
];

}
