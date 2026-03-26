<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpportunityStageHistory;
use App\Services\CustomerService;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'amount',
        'estimated_close_date',
        'description',
        'stage',
        'created_by',
    ];

    // Relación con los Historiales de Etapa
    public function stageHistories()
    {
        return $this->hasMany(OpportunityStageHistory::class)->orderBy('changed_at', 'desc');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}