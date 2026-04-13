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
