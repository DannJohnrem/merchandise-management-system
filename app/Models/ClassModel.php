<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'name',         // Readable name, e.g., Finance Dept, Juan Dela Cruz, Others A57-2
        'description',  // Optional details
        'type',         // Department / Employee / Client / Others
        'code',         // Optional code like A57-2
        'department',   // Optional department name
        'position',     // Optional position (for employee-specific)
    ];
}
