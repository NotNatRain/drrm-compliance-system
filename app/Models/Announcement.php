<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'what',
        'when',
        'where',
        'why',
        'image_path',
        'is_active'
    ];

    protected $casts = [
        'when' => 'datetime',
        'is_active' => 'boolean'
    ];
}
