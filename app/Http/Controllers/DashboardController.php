<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Opportunity;

class DashboardController extends Controller
{
    public function index()
    {
        // Tickets
        $ticketsTotal = Ticket::count();
        $ticketsAbiertos = Ticket::where('status', 'Abierto')->count();
        $ticketsAsignados = Ticket::where('status', 'Asignado')->count();

        // Oportunidades
        $opTotal = Opportunity::count();
        $opProspecto = Opportunity::where('stage', 'Prospecto')->count();
        $opNegociacion = Opportunity::where('stage', 'Negociación')->count();
        $opCerradoGanado = Opportunity::where('stage', 'Cerrado Ganado')->count();
        $opCerradoPerdido = Opportunity::where('stage', 'Cerrado Perdido')->count();

        return view('dashboard', compact(
            'ticketsTotal',
            'ticketsAbiertos',
            'ticketsAsignados',
            'opTotal',
            'opProspecto',
            'opNegociacion',
            'opCerradoGanado',
            'opCerradoPerdido'
        ));
    }
}
