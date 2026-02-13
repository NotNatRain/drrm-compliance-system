<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypFldFamilyMember extends Model
{
    protected $table = 'typ_fld_family_members';

    protected $fillable = [
        'family_id',
        'full_name',
        'age',
        'gender',
        'needs',
        'is_head',
        'status',
    ];

    protected $casts = [
        'age' => 'integer',
        'is_head' => 'boolean',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(TypFldFamily::class, 'family_id');
    }
}

