<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetyEvacuationDrill extends Model
{
    protected $fillable = [
        'school_id',
        'drill_type',
        'drill_date',
        'start_time',
        'end_time',
        'participants_count',
        'evacuation_time_minutes',
        'status',
        'remarks',
    ];
}
