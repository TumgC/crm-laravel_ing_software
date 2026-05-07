<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommercialStatusCheckLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'customer_id',
        'checked_by',
        'checked_at',
        'status',
        'stage_result',
        'opportunity_reference',
        'error_message',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
    ];
}