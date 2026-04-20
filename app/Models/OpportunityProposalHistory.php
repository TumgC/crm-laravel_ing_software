<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpportunityProposalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_id',
        'old_status',
        'new_status',
        'changed_by',
        'changed_at',
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    public function proposal()
    {
        return $this->belongsTo(OpportunityProposal::class, 'proposal_id');
    }
}