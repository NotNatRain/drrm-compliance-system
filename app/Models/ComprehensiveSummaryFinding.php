<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveSummaryFinding extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_sumFindings';

    protected $guarded = [];

    protected $casts = [
        'observation_date' => 'date',
        'floor_number' => 'integer',
        'chairs_count' => 'integer',
        'tables_count' => 'integer',
        'tv_count' => 'integer',
        'electric_fan_count' => 'integer',
        'ceiling_fan_count' => 'integer',
        'water_dispenser_count' => 'integer',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function building()
    {
        return $this->belongsTo(FireSafetyBuilding::class, 'building_id');
    }
}
