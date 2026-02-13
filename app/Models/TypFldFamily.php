<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypFldFamily extends Model
{
    protected $table = 'typ_fld_families';

    protected $fillable = [
        'evacuation_center_id',
        'head_family_name',
        'collective_needs',
        'has_pregnant',
        'has_pwd',
        'has_senior',
        'has_lactating',
        'has_child_under5',
        'checked_in_at',
        'checked_out_at',
    ];

    protected $casts = [
        'has_pregnant' => 'boolean',
        'has_pwd' => 'boolean',
        'has_senior' => 'boolean',
        'has_lactating' => 'boolean',
        'has_child_under5' => 'boolean',
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
    ];

    public function evacuationCenter(): BelongsTo
    {
        return $this->belongsTo(TypFldEvacuationCenter::class, 'evacuation_center_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TypFldFamilyMember::class, 'family_id');
    }
}

