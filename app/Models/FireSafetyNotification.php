<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetyNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'compliance_type',
        'module',
        'school_id',
        'user_id',
        'type',
        'title',
        'message',
        'action_type',
        'action_url',
        'action_data',
        'is_read',
    ];

    protected $casts = [
        'action_data' => 'array',
        'is_read' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForCompliance($query, $complianceType)
    {
        return $query->where('compliance_type', $complianceType);
    }

    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeExcludeTypes($query, array $types)
    {
        return $query->whereNotIn('type', $types);
    }
}
