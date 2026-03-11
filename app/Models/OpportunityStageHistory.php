<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpportunityStageHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'old_stage',
        'new_stage',
        'changed_by',
        'changed_at',
    ];
}
