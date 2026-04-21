<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportCheckLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'opportunity_id',
        'checked_by',
        'checked_at',
        'has_critical_ticket',
        'summary',
        'status',
        'error_message',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'has_critical_ticket' => 'boolean',
    ];
}
