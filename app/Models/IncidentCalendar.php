<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class IncidentCalendar extends Model
{
    protected $table = 'incident_calendars';

    protected $fillable = [
        'incident_date',
        'school_name',
        'entry_type',
        'incident_type_id',
        'incident_status_id',
        'remarks',
        'reported_by',
        'is_verified',
        'verified_at',
        'verified_by',
        'affected_personnel',
        'affected_students',
        'additional_data',
        'attachment_path',
        'attachment_name',
        'attachment_size',
        'attachment_mime'
    ];

    protected $casts = [
        'incident_date' => 'date',
        'verified_at' => 'datetime',
        'additional_data' => 'array',
        'is_verified' => 'boolean'
    ];

    public function incidentType()
    {
        return $this->belongsTo(IncidentType::class);
    }

    public function incidentStatus()
    {
        return $this->belongsTo(IncidentStatus::class);
    }

    protected function dayName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->incident_date->format('l')
        );
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('incident_date', $year)
                    ->whereMonth('incident_date', $month);
    }

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('incident_date', $date);
    }
    protected $appends = ['attachment_url'];
    public function getAttachmentUrlAttribute()
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }
}
