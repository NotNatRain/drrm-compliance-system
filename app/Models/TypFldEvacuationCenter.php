<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypFldEvacuationCenter extends Model
{
    protected $table = 'typ_fld_evacuation_centers';

    protected $fillable = [
        'school_id',
        'identification',
        'location',
        'capacity',
        'operational_status',
        'needs_summary',
        'occupancy_safety',
        'usage_status',
        'emergency_resources',
        'emergency_resources_usage_status',
        'monitoring_status',
        'reports_status',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }

    public function families(): HasMany
    {
        return $this->hasMany(TypFldFamily::class, 'evacuation_center_id');
    }

    public function monitoringSnapshots(): HasMany
    {
        return $this->hasMany(TypFldMonitoringSnapshot::class, 'evacuation_center_id');
    }
}

