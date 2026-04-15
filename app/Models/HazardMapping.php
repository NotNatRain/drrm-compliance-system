<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HazardMapping extends Model
{
    use HasFactory;

    protected $table = 'hzd_map_info';

    protected $fillable = [
        'school_id',
        'floor_number',
        'floor_name',
        'hazards',
        'vulnerabilities',
        'evacuation_routes',
        'assembly_points',
        'safe_zones',
        'hazard_zones',
        'notes',
        'map_data',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hazards' => 'array',
        'vulnerabilities' => 'array',
        'evacuation_routes' => 'array',
        'assembly_points' => 'array',
        'safe_zones' => 'array',
        'hazard_zones' => 'array',
        'map_data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
