<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function subdivision()
    {
        return $this->belongsTo(Subdivision::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
