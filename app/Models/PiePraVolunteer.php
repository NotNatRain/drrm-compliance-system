<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PiePraVolunteer extends Model
{
    protected $table = 'pie_pra_volunteers';

    protected $fillable = [
        'name',
        'contact',
        'barangay',
        'qr_token',
        'status',
    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(PiePraVolunteerSkill::class, 'pie_pra_volunteer_skill_pivot', 'volunteer_id', 'skill_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(PiePraVolunteerAssignment::class, 'volunteer_id');
    }
}

