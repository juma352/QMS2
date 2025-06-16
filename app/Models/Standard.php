<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Standard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'standard_name',
        'standard_number',
        'standard',
        'clause',
        'revision_version',
        'date_created',
        'date_of_issue',
        'next_revision_date',
        'next_date_of_issue',
        'standard_file',
        'other_documents',
    ];
}