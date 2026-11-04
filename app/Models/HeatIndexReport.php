<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeatIndexReport extends Model
{
    use HasFactory;

    protected $table = 'heat_index_reports';

    protected $fillable = [
        'school_id',
        'school_name',
        'school_head',
        'submitted_by',
        'user_id',
        'report_date',
        'report_time',
        'temperature',
        'humidity',
        'heat_index',
        'category',
        'badge_color',
        'action_taken',
        'remarks',
    ];

    protected $casts = [
        'report_date' => 'date',
        'temperature' => 'float',
        'humidity' => 'integer',
        'heat_index' => 'float',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
