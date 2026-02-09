<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentStatus extends Model
{
    protected $table = 'incident_statuses';
    
    protected $fillable = ['name', 'color_class', 'short_code', 'is_compliance'];
    
    public function incidents()
    {
        return $this->hasMany(IncidentCalendar::class);
    }
}