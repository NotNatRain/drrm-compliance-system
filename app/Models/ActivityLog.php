<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\School;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'role',
        'activity',
        'school_id',
        'school_name',
        'module',
        'notes',
    ];

    /**
     * Log an activity. Call from controllers after create/update/delete.
     *
    * @param string $module  fire_safety, typhoon_flood, incident_checklist, comprehensive_school_safety, hazard_mapping
     * @param string $activity  Human-readable action (e.g. "Created building", "Updated alarm")
     * @param array $options  school_id (int), school_name (string), notes (string)
     */
    public static function log(string $module, string $activity, array $options = []): ?self
    {
        $user = auth()->user();
        $schoolId = $options['school_id'] ?? ($options['unified_school_id'] ?? null);
        $schoolName = $options['school_name'] ?? null;

        // Resolve school name if we have a school id but no display name
        if ($schoolId && $schoolName === null) {
            $school = School::find($schoolId);
            $schoolName = $school ? $school->school_name : null;
        }

        return self::create([
            'user_id' => $user?->id,
            'role' => $user?->role,
            'activity' => $activity,
            'school_id' => $schoolId,
            'school_name' => $schoolName,
            'module' => $module,
            'notes' => $options['notes'] ?? null,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function getSchoolDisplayAttribute(): string
    {
        if (!empty($this->school_name)) {
            return (string) $this->school_name;
        }

        if ($this->relationLoaded('school') && $this->school) {
            return (string) ($this->school->school_name ?? '—');
        }

        return $this->school_id ? ('School #' . $this->school_id) : '—';
    }

    /**
     * Human-readable module label.
     */
    public function getModuleLabelAttribute(): string
    {
        return match ($this->module) {
            'fire_safety' => 'Fire Safety',
            'typhoon_flood' => 'Typhoon & Flood',
            'incident_checklist' => 'Incident Checklist',
            'comprehensive_school_safety' => 'Comprehensive School Safety',
            'comprehensive_safety' => 'Comprehensive School Safety',
            'hazard_mapping' => 'Hazard Mapping',
            default => ucfirst(str_replace('_', ' ', $this->module)),
        };
    }
}
