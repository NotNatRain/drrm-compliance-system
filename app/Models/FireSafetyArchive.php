<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetyArchive extends Model
{
    protected $fillable = [
        'unified_school_id',
        'type',
        'item_id',
        'item_code',
        'item_data',
        'reason',
        'removed_at'
    ];

    protected $casts = [
        'item_data' => 'array',
        'removed_at' => 'datetime'
    ];
}
