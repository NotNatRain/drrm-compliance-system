<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentType extends Model
{
    protected $table = 'incident_types';
    
    protected $fillable = ['name', 'color_class', 'description', 'priority'];
    
    public function incidents()
    {
        return $this->hasMany(IncidentCalendar::class);
    }
}