<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSatisfactionSurvey extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'customer_id',
        'rating',
        'comment',
        'created_by',
        'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}