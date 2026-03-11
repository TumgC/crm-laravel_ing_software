<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Services\CustomerService;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\TicketAssignment;
use Carbon\Carbon;


class TicketController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }


        public function create()
        {
            $statuses = [
                'Abierto',
                'Asignado',
                'En Proceso',
                'En Espera',
                'Esperando Respuesta',
                'Cerrado',
                'Resuelto',
            ];

            $agents = User::orderBy('name')->get(['id','name','email']);

            return view('tickets.create', compact('statuses', 'agents'));
        }


        public function store(Request $request)
        {
            $request->validate([
                'customer_id' => 'required|integer',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|max:50',
                'assigned_to' => 'nullable|integer|exists:users,id',
            ]);

            // Generar ticket_number (si ya lo generas en otro lado, no dupliques)
            $nextId = (Ticket::max('id') ?? 0) + 1;
            $ticketNumber = 'TCK-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

            Ticket::create([
                'ticket_number' => $ticketNumber,
                'customer_id' => $request->customer_id,
                'subject' => $request->subject,
                'description' => $request->description,
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
            ]);

            $status = $request->status;

            if ($request->filled('assigned_to')) {
                $status = 'Asignado';
            }

            return redirect()->route('tickets.index')->with('success', 'Ticket creado correctamente.');
        }


        public function index(\Illuminate\Http\Request $request)
        {
            $status = $request->query('status');   // ejemplo: Abierto, Asignado, etc.
            $q      = $request->query('q');        // texto de búsqueda

            $ticketsQuery = \App\Models\Ticket::with('assignee');

            // filtro por estado
            if ($status && $status !== 'Todos') {
                $ticketsQuery->where('status', $status);
            }

            // búsqueda por ticket_number / subject / description
            if ($q) {
                $ticketsQuery->where(function ($sub) use ($q) {
                    $sub->where('ticket_number', 'like', "%{$q}%")
                        ->orWhere('subject', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }

            $tickets = $ticketsQuery->orderByDesc('created_at')->paginate(10)->withQueryString();

            // lista de estados que me dijiste
            $statuses = [
                'Todos',
                'Abierto',
                'Asignado',
                'En Proceso',
                'En Espera',
                'Esperando Respuesta',
                'Cerrado',
                'Resuelto',
            ];

            return view('tickets.index', compact('tickets', 'statuses', 'status', 'q'));
        }

        public function assignForm(Ticket $ticket)
        {
            $agents = User::orderBy('name')->get(); // por ahora cualquier usuario puede ser "agente"
            return view('tickets.assign', compact('ticket', 'agents'));
        }

        public function assignStore(Request $request, Ticket $ticket)
        {
            $request->validate([
                'assigned_to' => 'required|exists:users,id',
            ]);

            // Actualizar ticket
            $ticket->update([
                'assigned_to' => $request->assigned_to,
                'status' => 'Asignado',
            ]);

            // Guardar historial
            TicketAssignment::create([
                'ticket_id' => $ticket->id,
                'assigned_by' => auth()->id(),
                'assigned_to' => $request->assigned_to,
                'assigned_at' => Carbon::now(),
            ]);

            return redirect()->route('tickets.index')->with('success', 'Ticket asignado correctamente.');
    }

        public function show(Ticket $ticket)
        {
            $agents = User::orderBy('name')->get();

            $statuses = [
                'Abierto',
                'Asignado',
                'En Proceso',
                'En Espera',
                'Esperando Respuesta',
                'Cerrado',
                'Resuelto',
            ];

            return view('tickets.show', compact('ticket', 'agents', 'statuses'));
        }

        public function update(Request $request, Ticket $ticket)
        {
            $request->validate([
                'status' => 'required|string|max:50',
                'assigned_to' => 'nullable|integer|exists:users,id',
            ]);

            $ticket->update([
                'status' => $request->status,
                'assigned_to' => $request->assigned_to,
            ]);

            return redirect()
                ->route('tickets.show', $ticket)
                ->with('success', 'Ticket actualizado correctamente.');
        }


}
