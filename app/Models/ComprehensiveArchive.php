<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveArchive extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_archives';

    protected $guarded = [];

    protected $casts = [
        'payload' => 'array',
        'archived_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
