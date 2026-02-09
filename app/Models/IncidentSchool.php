<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentSchool extends Model
{
    protected $table = 'incident_schools';
    
    protected $fillable = ['name', 'district', 'division', 'region', 'school_id', 'incident_count', 'last_incident_date'];
    
    public function incidents()
    {
        return $this->hasMany(IncidentCalendar::class, 'school_name', 'name');
    }
}