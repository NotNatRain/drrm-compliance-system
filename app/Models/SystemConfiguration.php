<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    protected $fillable = [
        'config_type',
        'parent_id',
        'name',
        'description',
        'code',
        'category',
        'color_class',
        'sort_order',
        'is_active',
        'min_floors',
        'total_rooms',
        'pressure_min',
        'pressure_max',
        'max_rooms_covered',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function parent()
    {
        return $this->belongsTo(SystemConfiguration::class, 'parent_id');
    }
}
