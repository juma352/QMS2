<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'document_name',
        'file_path',
    ];

    /**
     * Get the program that owns the document.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
