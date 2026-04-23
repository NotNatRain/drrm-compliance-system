<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TypFldNeed extends Model
{
    protected $table = 'typ_fld_needs';

    protected $fillable = [
        'family_id',
        'family_member_id',
        'need_name',
        'quantity',
        'is_custom',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'is_custom' => 'boolean',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(TypFldFamily::class, 'family_id');
    }

    public function familyMember(): BelongsTo
    {
        return $this->belongsTo(TypFldFamilyMember::class, 'family_member_id');
    }
}