<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStorage extends Model
{
    use HasFactory;

    protected $table = 'inventory_storage';

    protected $fillable = [
        'school_id',
        'item_name',
        'unit',
        'quantity',
        'status',
        'location',
        'fund_source',
        'date_received',
        'date_checked'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}