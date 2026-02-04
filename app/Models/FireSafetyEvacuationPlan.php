<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FireSafetyEvacuationPlan extends Model
{
    protected $table = 'firesafety_evacuationplans';
    
    protected $fillable = [
        'school_id',
        'building_id',
        'plan_no',
        'exits',
        'routes',
        'areas',
        'primary_route',
        'secondary_route',
        'primary_assembly_area',
        'secondary_assembly_area',
        'assembly_capacity',
        'emergency_contacts',
        'special_instructions',
        'status',
        'approved_at',
        'map_data'
    ];

    protected $casts = [
        'exits' => 'integer',
        'routes' => 'integer',
        'areas' => 'integer',
        'assembly_capacity' => 'integer',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function school(): BelongsTo
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }

    public function building(): BelongsTo
    {
        return $this->belongsTo(FireSafetyBuilding::class, 'building_id');
    }
    
    // Helper methods
    public function getStatusLabelAttribute(): string
    {
        $status = $this->calculateStatus();
        return match($status) {
            'outdated' => 'Outdated',
            'needs_update' => 'Needs Update',
            'current' => 'Current/Valid',
            'due_for_review' => 'Due for Review',
            'draft' => 'Draft',
            'review' => 'Under Review',
            'inactive' => 'Inactive',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        $status = $this->calculateStatus();
        return match($status) {
            'outdated' => 'danger',
            'needs_update' => 'warning',
            'current' => 'success',
            'due_for_review' => 'info',
            'draft' => 'secondary',
            'review' => 'warning',
            'inactive' => 'danger',
            default => 'secondary'
        };
    }

    public function calculateStatus(): string
    {
        if ($this->status !== 'active' || !$this->approved_at) {
            return $this->status; // draft, review, inactive
        }

        $now = now();
        $yearsSinceApproval = $this->approved_at->diffInYears($now);

        if ($yearsSinceApproval >= 5) {
            return 'outdated';
        }

        if ($yearsSinceApproval >= 4) {
            return 'due_for_review';
        }

        // Check if building or its components were updated after approval
        if ($this->building) {
            if ($this->building->updated_at > $this->approved_at) {
                return 'needs_update';
            }
            
            $latestAlarm = $this->building->alarmSystems()->latest('updated_at')->first();
            if ($latestAlarm && $latestAlarm->updated_at > $this->approved_at) {
                return 'needs_update';
            }

            $latestExt = $this->building->fireExtinguishers()->latest('updated_at')->first();
            if ($latestExt && $latestExt->updated_at > $this->approved_at) {
                return 'needs_update';
            }
        }

        return 'current';
    }
    
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('F d, Y') : 'N/A';
    }
    
    public function getFormattedUpdatedAtAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->format('F d, Y') : 'N/A';
    }
    
    public function getSafetyEquipmentSummaryAttribute(): array
    {
        if (!$this->building) {
            return [
                'emergency_exits' => 0,
                'functional_alarms' => 0,
                'active_extinguishers' => 0,
                'total_rooms' => 0,
                'required_extinguishers' => 0
            ];
        }
        
        return [
            'emergency_exits' => $this->building->emergency_exits ?? 0,
            'functional_alarms' => $this->building->functionalAlarmsCount ?? 0,
            'active_extinguishers' => $this->building->activeExtinguishersCount ?? 0,
            'total_rooms' => $this->building->rooms ?? 0,
            'required_extinguishers' => max(1, (int) ceil(($this->building->rooms ?? 0) / 3))
        ];
    }
}