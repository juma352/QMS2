<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CqiProject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_name',
        'description',
        'mission',
        'project_leader',
        'methodology',
        'problem_statement',
        'smart_goals',
        'metrics_to_track',
        'data_collection_method',
        'initial_progress',
        'status',
    ];
}