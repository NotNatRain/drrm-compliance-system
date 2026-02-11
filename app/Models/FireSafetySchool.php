<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FireSafetySchool extends Model
{
    protected $table = 'firesafety_school_information';
    
    protected $fillable = [
        'school_name', 
        'school_id', 
        'address', 
        'school_head', 
        'school_drrm_coordinator', 
        'status',
        'evacuation_map_layout',
        'alerts',
        'events'
    ];

    protected $casts = [
        'status' => 'string',
        'evacuation_map_layout' => 'array',
        'alerts' => 'array',
        'events' => 'array'
    ];

    // Relationships
    public function extinguishers(): HasMany
    {
        return $this->hasMany(FireSafetyExtinguisher::class, 'school_id');
    }

    public function alarmSystems(): HasMany
    {
        return $this->hasMany(FireSafetyAlarmSystem::class, 'school_id');
    }

    public function buildings(): HasMany
    {
        return $this->hasMany(FireSafetyBuilding::class, 'school_id');
    }

    public function evacuationPlans(): HasMany
    {
        return $this->hasMany(FireSafetyEvacuationPlan::class, 'school_id');
    }
    
    public function rooms(): HasMany
    {
        return $this->hasMany(FireSafetyRoom::class, 'school_id');
    }

    public function inspections(): HasMany
    {
        return $this->hasMany(FireSafetyInspection::class, 'school_id');
    }

    public function drills(): HasMany
    {
        return $this->hasMany(FireSafetyEvacuationDrill::class, 'school_id');
    }
    
    // Helper methods for evacuation plans page
    public function getBuildingsWithPlansCountAttribute(): int
    {
        return $this->buildings()->whereHas('evacuationPlan', function($query) {
            $query->where('status', 'active');
        })->count();
    }
    
    public function getTotalEmergencyExitsAttribute(): int
    {
        return $this->buildings()->sum('emergency_exits');
    }
    
    public function getTotalFunctionalAlarmsAttribute(): int
    {
        return $this->buildings()->withCount(['alarmSystems as functional_count' => function($query) {
            $query->whereIn('status', ['functional', 'online']);
        }])->get()->sum('functional_count');
    }
    
    public function getTotalActiveExtinguishersAttribute(): int
    {
        return $this->buildings()->withCount(['fireExtinguishers as active_count' => function($query) {
            $query->where('status', 'active');
        }])->get()->sum('active_count');
    }
    
    public function getEvacuationCoveragePercentageAttribute(): float
    {
        $totalBuildings = $this->buildings()->count();
        if ($totalBuildings === 0) return 0;
        
        return round(($this->buildingsWithPlansCount / $totalBuildings) * 100, 1);
    }
    
    public function getCoverageStatusAttribute(): string
    {
        $percentage = $this->evacuationCoveragePercentage;
        
        if ($percentage >= 80) return 'good';
        if ($percentage >= 50) return 'fair';
        return 'poor';
    }
    
    public function getCoverageStatusColorAttribute(): string
    {
        return match($this->coverageStatus) {
            'good' => 'success',
            'fair' => 'warning',
            'poor' => 'danger',
            default => 'secondary'
        };
    }
    
    public function getSchoolStatusLabelAttribute(): string
    {
        return match($this->status) {
            'passed' => 'PASSED',
            'failed' => 'FAILED',
            'unconfigured' => 'UNCONFIGURED',
            'warning' => 'WARNING',
            default => 'UNKNOWN'
        };
    }
    
    public function getSchoolStatusColorAttribute(): string
    {
        return match($this->status) {
            'passed' => 'success',
            'failed' => 'danger',
            'unconfigured' => 'warning',
            'warning' => 'warning',
            default => 'secondary'
        };
    }
    
    public function getFormattedAddressAttribute(): string
    {
        return nl2br(e($this->address));
    }
}