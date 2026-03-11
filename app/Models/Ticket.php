<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ticket extends Model
{
    use HasFactory;

    // Campos permitidos para creación masiva (Ticket::create)
    protected $fillable = [
        'ticket_number',
        'customer_id',
        'subject',
        'description',
        'status',
        'assigned_to',
    ];

    // (Opcional recomendado) relación: ticket asignado a un usuario (agente)
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // (Opcional recomendado) historial de asignaciones (para tu Historia 2 Soporte)
    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }
}
