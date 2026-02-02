<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FireSafetyExtinguisherInspection extends Model
{
    protected $table = 'fire_safety_extinguisher_inspections';

    protected $fillable = [
        'extinguisher_id',
        'user_id',
        'inspection_date',
        'status',
        'pressure_level',
        'notes'
    ];

    public function extinguisher()
    {
        return $this->belongsTo(FireSafetyExtinguisher::class, 'extinguisher_id');
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
