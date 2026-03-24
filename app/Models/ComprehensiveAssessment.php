<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveAssessment extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_assessments';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(\App\Models\ComprehensiveSchool::class, 'school_id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\ComprehensiveAssessmentItem::class, 'assessment_id');
    }
}
