<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveFacility extends Model
{
    use HasFactory;

    protected $table = 'cmpr_schl_sfty_facilities';

    protected $guarded = [];

    public function school()
    {
        return $this->belongsTo(ComprehensiveSchool::class, 'school_id');
    }
}
