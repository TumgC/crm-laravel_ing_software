<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OpportunityProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'opportunity_id',
        'title',
        'file_path',
        'file_name',
        'uploaded_at',
        'uploaded_by',
        'status',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function histories()
    {
        return $this->hasMany(OpportunityProposalHistory::class, 'proposal_id');
    }
}