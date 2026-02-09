<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FireSafetyExtinguisher extends Model
{
    protected $table = 'firesafety_fire_extinguishers';

    protected $fillable = [
        'school_id',
        'building_id',
        'room_id',
        'code',
        'type',
        'status',
        'pressure_level',
        'date_checked',
        'evaluation_result',
        'remarks',
        'notes'
    ];

    protected $casts = [
        'pressure_level' => 'integer',
        'date_checked' => 'date'
    ];

    // Relationships
    public function building(): BelongsTo
    {
        return $this->belongsTo(FireSafetyBuilding::class, 'building_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }

    public function centerRoom(): BelongsTo
    {
        return $this->belongsTo(FireSafetyRoom::class, 'room_id');
    }

    // Alias for backward compatibility or if code refers to 'room'
    public function room(): BelongsTo
    {
        return $this->belongsTo(FireSafetyRoom::class, 'room_id');
    }

    public function coveredRooms(): BelongsToMany
    {
        return $this->belongsToMany(
            FireSafetyRoom::class,
            'fire_safety_extinguisher_room_coverage',
            'extinguisher_id',
            'room_id'
        )->withTimestamps();
    }
    
    public function inspections()
    {
        return $this->hasMany(FireSafetyExtinguisherInspection::class, 'extinguisher_id');
    }
    
    // Helper methods
    public function getHealthClassAttribute(): string
    {
        $pressure = $this->pressure_level ?? 100;
        
        if ($pressure <= 10) return 'health-empty';
        if ($pressure < 70) return 'health-critical';
        if ($pressure < 90) return 'health-low';
        return 'health-good';
    }
    
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'maintenance' => 'warning',
            'expired' => 'danger',
            'missing' => 'secondary',
            default => 'secondary'
        };
    }
    
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'maintenance' => 'For Refill',
            'expired' => 'Expired',
            'missing' => 'Missing',
            default => 'Unknown'
        };
    }
    
    public function getCoveredRoomsCountAttribute(): int
    {
        return $this->coveredRooms()->count();
    }
    
    public function getFormattedDateCheckedAttribute(): ?string
    {
        return $this->date_checked ? $this->date_checked->format('M d, Y') : 'N/A';
    }
    
    public function getPressureStatusAttribute(): string
    {
        $pressure = $this->pressure_level ?? 100;
        
        if ($pressure >= 90) return 'good';
        if ($pressure >= 70) return 'fair';
        if ($pressure >= 10) return 'low';
        return 'empty';
    }
    
    public function getPressureStatusColorAttribute(): string
    {
        return match($this->pressureStatus) {
            'good' => 'success',
            'fair' => 'warning',
            'low' => 'warning',
            'empty' => 'danger',
            default => 'secondary'
        };
    }
    
    public function getCoverageInfoAttribute(): array
    {
        $centerRoom = $this->centerRoom;
        $coveredRooms = $this->coveredRooms;
        
        return [
            'center_room' => $centerRoom ? $centerRoom->room_name : 'N/A',
            'center_room_type' => $centerRoom ? $centerRoom->room_type : 'N/A',
            'covered_count' => $coveredRooms->count(),
            'covered_rooms' => $coveredRooms->pluck('room_name')->toArray(),
            'room_types' => $coveredRooms->pluck('room_type')->toArray()
        ];
    }
}