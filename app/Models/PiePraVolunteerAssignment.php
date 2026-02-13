<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PiePraVolunteerAssignment extends Model
{
    protected $table = 'pie_pra_volunteer_assignments';

    protected $fillable = [
        'scenario_id',
        'volunteer_id',
        'school_id',
        'role',
        'status',
        'check_in_at',
        'check_out_at',
        'certificate_issued_at',
        'certificate_number',
    ];

    protected $casts = [
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'certificate_issued_at' => 'datetime',
    ];

    public function scenario(): BelongsTo
    {
        return $this->belongsTo(PiePraScenario::class, 'scenario_id');
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(PiePraVolunteer::class, 'volunteer_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(FireSafetySchool::class, 'school_id');
    }
}

