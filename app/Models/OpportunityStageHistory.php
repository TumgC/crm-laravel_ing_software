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

    // Aseguramos que `changed_at` se trate como una fecha
    protected $dates = ['changed_at'];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}