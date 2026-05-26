<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TicketSatisfactionSurvey;

class Ticket extends Model
{
    use HasFactory;

protected $fillable = [
    'ticket_number',
    'customer_id',
    'customer_name',
    'subject',
    'description',
    'priority',
    'status',
    'closed_at',
    'assigned_to',

];

    public function satisfactionSurvey()
{
    return $this->hasOne(TicketSatisfactionSurvey::class);
}

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(TicketStatusHistory::class)->orderByDesc('changed_at');
    }

    public function scopeByPriority($query, ?string $priority)
    {
        if ($priority && $priority !== 'Todas') {
            $query->where('priority', $priority);
        }

        return $query;
    }

    public function scopeByStatus($query, ?string $status)
    {
        if ($status && $status !== 'Todos') {
            $query->where('status', $status);
        }

        return $query;
    }

    public function scopeByAssignee($query, ?string $assignedTo)
    {
        if ($assignedTo === 'unassigned') {
            $query->whereNull('assigned_to');
        } elseif ($assignedTo) {
            $query->where('assigned_to', $assignedTo);
        }

        return $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if ($term) {
            $query->where(function ($sub) use ($term) {
                $sub->where('ticket_number', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('customer_id', 'like', "%{$term}%");
            });
        }

        return $query;
    }
}
