<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'attached_evacuation_map',
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
    public function extinguishers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FireSafetyExtinguisher::class, 'school_id');
    }

    public function alarmSystems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FireSafetyAlarmSystem::class, 'school_id');
    }

    public function buildings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FireSafetyBuilding::class, 'school_id');
    }

    public function evacuationPlans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FireSafetyEvacuationPlan::class, 'school_id');
    }

    public function schoolEvacuationPlan(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FireSafetyEvacuationPlan::class, 'school_id')->whereNull('building_id')->where('status', 'active');
    }
    
    public function rooms(): \Illuminate\Database\Eloquent\Relations\HasMany
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
        // If there's an active school-wide plan, all buildings are technically covered
        if ($this->schoolEvacuationPlan) {
            return $this->buildings()->count();
        }

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
        return $this->alarmSystems()->whereIn('status', ['active', 'functional', 'online'])->count();
    }
    
    public function getTotalActiveExtinguishersAttribute(): int
    {
        return $this->extinguishers()->where('status', 'active')->count();
    }
    
    public function getEvacuationCoveragePercentageAttribute(): float
    {
        if ($this->schoolEvacuationPlan) {
            return 100.0;
        }

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
            'unconfigured' => 'UNCONFIGURED',
            'warning', 'failed' => 'ONGOING IMPROVEMENT',
            default => 'UNKNOWN'
        };
    }
    
    public function getSchoolStatusColorAttribute(): string
    {
        return match($this->status) {
            'passed' => 'success',
            'unconfigured' => 'secondary',
            'warning', 'failed' => 'warning',
            default => 'info'
        };
    }
    
    public function getFormattedAddressAttribute(): string
    {
        return nl2br(e($this->address));
    }
}