<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    use HasFactory;

    // Campos permitidos para la asignación masiva
    protected $fillable = [
        'comment',
        'ticket_id',
        'user_id',
    ];

    // Definir la relación con el ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Definir la relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}