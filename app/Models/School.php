<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * This defines the relationship to the Subdivision.
     * It's the critical missing piece.
     */
    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
