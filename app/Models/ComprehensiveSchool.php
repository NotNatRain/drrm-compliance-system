<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveSchool extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_schools';

    protected $fillable = [
        'school_id_number',
        'name',
        'address',
        'district',
        'division',
        'region',
        'school_head',
        'contact_number',
    ];

    public function students()
    {
        return $this->hasMany(\App\Models\ComprehensiveStudent::class, 'school_id');
    }

    public function facilities()
    {
        return $this->hasMany(\App\Models\ComprehensiveFacility::class, 'school_id');
    }

    public function assessments()
    {
        return $this->hasMany(\App\Models\ComprehensiveAssessment::class, 'school_id');
    }
}
