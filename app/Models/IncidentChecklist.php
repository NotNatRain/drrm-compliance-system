<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentChecklist extends Model
{
    protected $table = 'incident_checklists';

    protected $fillable = [
        'user_id',
        'checklist_date',
        'label',
        'is_completed',
        'sort_order',
    ];

    protected $casts = [
        'checklist_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

