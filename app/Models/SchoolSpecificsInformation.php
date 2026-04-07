<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSpecificsInformation extends Model
{
    use HasFactory;
    
    protected $table = 'school_specifics_information';

    protected $fillable = [
        'school_id',
        'module',
        'key',
        'value'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
