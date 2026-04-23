<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\TypFldNeed;


class TypFldFamily extends Model
{
    protected $table = 'typ_fld_families';

    protected $fillable = [
        'school_id',
        'head_family_name',
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

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(TypFldFamilyMember::class, 'family_id');
    }

    public function needs(): HasMany
    {
        return $this->hasMany(TypFldNeed::class, 'family_id');
    }

    public function getNeedsSummaryAttribute(): string
    {
        return $this->needs
            ->map(function (TypFldNeed $need) {
                $label = $need->need_name;

                if ((int) $need->quantity > 1) {
                    $label .= ' x' . $need->quantity;
                }

                return $label;
            })
            ->filter()
            ->implode(', ');
    }
}

