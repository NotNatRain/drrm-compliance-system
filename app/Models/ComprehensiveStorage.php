<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveStorage extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_storage';

    protected $guarded = [];

    protected $casts = [
        'is_available' => 'boolean',
        'is_functional' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
