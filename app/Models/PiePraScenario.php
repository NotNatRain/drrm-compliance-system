<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PiePraScenario extends Model
{
    protected $table = 'pie_pra_scenarios';

    protected $fillable = [
        'name',
        'hazard_type',
        'lead_time_hours',
        'status',
        'created_by',
    ];

    public function recommendations(): HasMany
    {
        return $this->hasMany(PiePraRecommendation::class, 'scenario_id');
    }
}

