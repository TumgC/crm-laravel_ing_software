<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'amount',
        'estimated_close_date',
        'description',
        'stage',
        'created_by',
    ];
}
