<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveAssessmentItem extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_assessment_items';

    protected $guarded = [];

    public function assessment()
    {
        return $this->belongsTo(\App\Models\ComprehensiveAssessment::class, 'assessment_id');
    }
}
