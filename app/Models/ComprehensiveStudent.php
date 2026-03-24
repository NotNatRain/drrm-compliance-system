<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveStudent extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_students';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(\App\Models\ComprehensiveSchool::class, 'school_id');
    }

    public function pathways()
    {
        return $this->hasMany(\App\Models\ComprehensiveStudentPathway::class, 'student_id');
    }
}
