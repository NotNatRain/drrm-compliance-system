<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetySchoolSnapshot extends Model
{
    protected $table = 'firesafety_school_snapshots';

    protected $fillable = [
        'school_id_code',
        'school_name',
        'full_data',
        'deleted_by',
        'reason',
        'deleted_at'
    ];

    protected $casts = [
        'full_data' => 'array',
        'deleted_at' => 'datetime'
    ];
}
