<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetyInspection extends Model
{
    protected $table = 'fire_safety_inspections';

    protected $fillable = [
        'school_id',
        'drill_type',
        'inspection_date',
        'inspection_time',
        'time_started',
        'time_finished',
        'elapsed_time',
        'no_of_exits',
        'no_of_buildings',
        'no_of_students',
        'no_of_personnel',
        'monitored_by',
        'checklist_data',
        'observers_data',
        'remarks',
        'coordinator_name',
        'school_head_name'
    ];

    protected $casts = [
        'checklist_data' => 'array',
        'observers_data' => 'array',
        'inspection_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }
}
