<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OpportunityProposal;

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

    public function stageHistories()
    {
        return $this->hasMany(OpportunityStageHistory::class)
            ->orderBy('changed_at', 'desc');
    }

    public function proposals()
    {
        return $this->hasMany(OpportunityProposal::class);
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