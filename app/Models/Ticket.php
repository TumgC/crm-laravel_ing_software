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

    // Relación con el agente asignado al ticket (relación de tipo 'belongsTo')
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Relación con el historial de asignaciones (para tu Historia 2 Soporte)
    public function assignments()
    {
        return $this->hasMany(TicketAssignment::class);
    }

    // Relación con las interacciones del ticket (comentarios/interacciones)
    public function interactions()
    {
        return $this->hasMany(Interaction::class);  // Asegúrate de tener la clase Interaction
    }
}
