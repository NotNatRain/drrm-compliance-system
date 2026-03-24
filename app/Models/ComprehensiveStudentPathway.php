<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveStudentPathway extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_student_pathways';

    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(\App\Models\ComprehensiveStudent::class, 'student_id');
    }
}
