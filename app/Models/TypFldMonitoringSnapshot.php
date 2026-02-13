<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypFldMonitoringSnapshot extends Model
{
    protected $table = 'typ_fld_monitoring_snapshots';

    protected $fillable = [
        'evacuation_center_id',
        'type',
        'payload',
        'recorded_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'recorded_at' => 'datetime',
    ];

    public function evacuationCenter(): BelongsTo
    {
        return $this->belongsTo(TypFldEvacuationCenter::class, 'evacuation_center_id');
    }
}

