<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalNotification extends Model
{
    protected $fillable = [
        'user_id',
        'opportunity_id',
        'title',
        'message',
        'read_at',
    ];

    public function opportunity()
    {
        return $this->belongsTo(Opportunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}