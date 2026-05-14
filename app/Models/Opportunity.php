<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpportunityProposal;
use App\Models\InternalNotification;
use App\Models\User;

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
        'closed_at',
        'closed_by',
        'final_amount',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
        'estimated_close_date' => 'date',
    ];

    public function stageHistories()
    {
        return $this->hasMany(OpportunityStageHistory::class)
            ->orderBy('changed_at', 'desc');
    }

    public function proposals()
    {
        return $this->hasMany(OpportunityProposal::class);
    }

    public function internalNotifications()
    {
        return $this->hasMany(InternalNotification::class);
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function scopeByStage($query, ?string $stage)
    {
        if ($stage && $stage !== 'Todos') {
            $query->where('stage', $stage);
        }

        return $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where(function ($sub) use ($term) {
                $sub->where('description', 'like', "%{$term}%")
                    ->orWhere('customer_id', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhere('amount', 'like', "%{$term}%");
            });
        }

        return $query;
    }
}