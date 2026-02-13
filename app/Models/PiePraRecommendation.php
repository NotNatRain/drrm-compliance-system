<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiePraRecommendation extends Model
{
    protected $table = 'pie_pra_recommendations';

    protected $fillable = [
        'scenario_id',
        'school_id',
        'activate_as_evac_center',
        'priority_score',
        'recommended_suspend_classes_at',
        'recommended_start_evac_at',
        'preposition_resources',
        'academic_continuity_notes',
    ];

    protected $casts = [
        'activate_as_evac_center' => 'boolean',
        'preposition_resources' => 'array',
        'recommended_suspend_classes_at' => 'datetime',
        'recommended_start_evac_at' => 'datetime',
    ];

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(PiePraScenario::class, 'scenario_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }
}

