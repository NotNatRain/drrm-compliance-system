<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FireSafetyBuilding extends Model
{
    protected $table = 'firesafety_buildings';

    protected $fillable = [
        'school_id',
        'building_no',
        'building_name',
        'floors',
        'rooms',
        'max_floors',
        'max_rooms',
        'year_constructed',
        'last_renovation',
        'emergency_exits',
        'building_type',
        'description',
        'features',
        'required_extinguishers',
        // compliance / scoring fields
        'safety_score',
        'compliance_status',
        'compliance_reason'
    ];

    protected $casts = [
        'floors' => 'integer',
        'rooms' => 'integer',
        'max_floors' => 'integer',
        'max_rooms' => 'integer',
        'year_constructed' => 'integer',
        'last_renovation' => 'integer',
        'emergency_exits' => 'integer',
        'safety_score' => 'integer'
    ];

    // Relationships
    public function school()
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }

    public function alarmSystems(): HasMany
    {
        return $this->hasMany(FireSafetyAlarmSystem::class, 'building_id');
    }

    public function alarmSystemsMany(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FireSafetyAlarmSystem::class, 'fire_safety_alarm_building', 'building_id', 'alarm_id');
    }

    public function fireExtinguishers(): HasMany
    {
        return $this->hasMany(FireSafetyExtinguisher::class, 'building_id');
    }

    public function actualRooms(): HasMany
    {
        return $this->hasMany(FireSafetyRoom::class, 'building_id');
    }

    public function drills(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(FireSafetyEvacuationDrill::class, 'fire_safety_drill_building', 'building_id', 'drill_id');
    }

    // Add this new relationship
    public function evacuationPlan(): HasOne
    {
        return $this->hasOne(FireSafetyEvacuationPlan::class, 'building_id');
    }

    // Helper methods
    public function getFunctionalAlarmsCountAttribute(): int
    {
        return $this->alarmSystems()->where('status', 'active')->count();
    }

    public function getActiveExtinguishersCountAttribute(): int
    {
        return $this->fireExtinguishers()->where('status', 'active')->count();
    }

    public function getRequiredExtinguishersCountAttribute(): int
    {
        if ($this->required_extinguishers > 0) {
            return $this->required_extinguishers;
        }
        return max(1, (int) ceil(($this->rooms ?? 0) / 3));
    }

    public function getSafetyScoreAttribute(): int
    {
        // Weighted scoring updated per request:
        // - Extinguishers: up to 45 points (proportional to requirement)
        // - Alarms: 45 points (binary: functional alarm present at building or school)
        // - Plans: up to 10 points, where a school-level plan grants 5 points and a building-level plan grants additional 5 points
        // This makes a building with extinguishers + alarm + school plan score 95 (Passed).

        $required = max(1, $this->requiredExtinguishersCount ?: 1);
        $extCount = $this->activeExtinguishersCount;

        // Extinguisher score: proportional up to 45
        $extRatio = min(1, $extCount / $required);
        $extScore = (int) round($extRatio * 45);

        // Alarm score: 45 if building or school has a functional/active alarm
        $schoolHasAlarm = $this->school->alarmSystems()->where('status', 'active')->exists();
        $alarmScore = ($this->functionalAlarmsCount > 0 || $schoolHasAlarm) ? 45 : 0;

        // Plan score: school-level = 5, building-level = additional 5
        $schoolHasPlan = FireSafetyEvacuationPlan::where('school_id', $this->school_id)
            ->whereNull('building_id')
            ->where('status', 'active')
            ->exists();
        $buildingHasPlan = $this->hasEvacuationPlan();

        $planScore = 0;
        if ($schoolHasPlan) $planScore += 5;
        if ($buildingHasPlan) $planScore += 5;

        $score = $extScore + $alarmScore + $planScore;

        // Cap at 100
        return min(100, $score);
    }

    public function getSafetyStatusAttribute(): string
    {
        $score = $this->safetyScore;

        if ($score >= 80) return 'good';
        if ($score >= 60) return 'fair';
        return 'poor';
    }

    public function getSafetyStatusLabelAttribute(): string
    {
        return match($this->safetyStatus) {
            'good' => 'Good',
            'fair' => 'Fair',
            'poor' => 'Poor',
            default => 'Unknown'
        };
    }

    public function getSafetyStatusColorAttribute(): string
    {
        return match($this->safetyStatus) {
            'good' => 'success',
            'fair' => 'warning',
            'poor' => 'danger',
            default => 'secondary'
        };
    }

    public function hasEvacuationPlan(): bool
    {
        return $this->evacuationPlan()->exists();
    }

    public function getEvacuationPlanStatusAttribute(): ?string
    {
        if (!$this->hasEvacuationPlan()) {
            return null;
        }

        return $this->evacuationPlan->status;
    }

    public function getEvacuationPlanStatusColorAttribute(): ?string
    {
        if (!$this->hasEvacuationPlan()) {
            return 'danger';
        }

        return $this->evacuationPlan->statusColor;
    }

    public function getEvacuationPlanStatusLabelAttribute(): ?string
    {
        if (!$this->hasEvacuationPlan()) {
            return 'No Plan';
        }

        return $this->evacuationPlan->statusLabel;
    }

    public function getFeaturesArrayAttribute(): array
    {
        if (empty($this->features)) {
            return [];
        }

        if (is_array($this->features)) {
            return $this->features;
        }

        try {
            $features = json_decode($this->features, true);
            return is_array($features) ? $features : [];
        } catch (\Exception $e) {
            return explode(',', $this->features);
        }
    }
}
