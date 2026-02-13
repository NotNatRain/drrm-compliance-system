<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PiePraVolunteerSkill extends Model
{
    protected $table = 'pie_pra_volunteer_skills';

    protected $fillable = [
        'name',
        'category',
    ];

    public function volunteers(): BelongsToMany
    {
        return $this->belongsToMany(PiePraVolunteer::class, 'pie_pra_volunteer_skill_pivot', 'skill_id', 'volunteer_id');
    }
}

