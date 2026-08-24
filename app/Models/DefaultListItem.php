<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefaultListItem extends Model
{
    protected $fillable = [
        'school_id',
        'section',
        'item_key',
        'item_name',
        'has_item',
        'quantity_owned',
        'source',
        'source_detail',
        'date_checked',
        'remarks',
    ];

    protected $casts = [
        'has_item'       => 'boolean',
        'quantity_owned' => 'integer',
        'date_checked'   => 'date',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}
