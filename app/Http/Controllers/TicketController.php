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
            // Validación de los datos, incluida la validación del campo 'frequent_problem'
            $request->validate([
                'customer_id' => 'required|integer',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'status' => 'required|string|max:50',
                'assigned_to' => 'nullable|integer|exists:users,id',
                'frequent_problem' => 'nullable|string', // Validación para el campo 'frequent_problem'
            ]);

            // Generar ticket_number (si ya lo generas en otro lado, no dupliques)
            $nextId = (Ticket::max('id') ?? 0) + 1;
            $ticketNumber = 'TCK-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

            // Crear el ticket con la descripción actualizada si se selecciona un 'frequent_problem'
            Ticket::create([
                'ticket_number' => $ticketNumber, // Asignar número de ticket
                'customer_id' => $request->customer_id, // Asignar ID del cliente
                'subject' => $request->subject, // Asunto del ticket
                'description' => $request->frequent_problem 
                                ? 'El cliente reporta el siguiente problema: ' . $request->frequent_problem 
                                : $request->description, // Descripción con el problema frecuente, si existe
                'status' => $request->status, // Estado del ticket (Abierto, Asignado, etc.)
                'assigned_to' => $request->assigned_to, // Asignar empleado si se seleccionó
            ]);

            // Determinar el estado del ticket (si se asignó un empleado, cambiar a 'Asignado')
            $status = $request->status;

                if ($request->filled('assigned_to')) {
                    $status = 'Asignado';
                    }

            // Redirigir con mensaje de éxito
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

            // lista de estados 
            $statuses = 
            [
            'Abierto',
            'Asignado',
            'En Proceso',
            'En Espera',
            'Esperando Respuesta',
            'Cerrado',
            'Resuelto',
            ];

            $statusCounts = array_fill_keys($statuses, 0);

            // Conteos reales por estado
            $realCounts = \App\Models\Ticket::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            // Mezclar conteos reales con los estados fijos
            foreach ($realCounts as $status => $total) {
                if (array_key_exists($status, $statusCounts)) {
                    $statusCounts[$status] = $total;
                }
        }

            // Conteo total general
            $totalTickets = \App\Models\Ticket::count();
            
            return view('tickets.index', compact('tickets', 'statuses', 'status', 'q', 'statusCounts','totalTickets'));
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
            // Obtener los agentes (usuarios)
            $agents = User::orderBy('name')->get();

            // Los estados posibles del ticket
            $statuses = [
                'Abierto',
                'Asignado',
                'En Proceso',
                'En Espera',
                'Esperando Respuesta',
                'Cerrado',
                'Resuelto',
            ];

            // Retornar la vista con los datos del ticket y demás variables
            return view('tickets.show', compact('ticket', 'agents', 'statuses'));
        
            {
            $ticket->load('interactions');  // Cargar las interacciones

            return view('tickets.show', compact('ticket'));
            }
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
    public function searchCustomers(Request $request, CustomerService $customerService)
        {
             $term = $request->get('term', '');

             $customers = $customerService->searchCustomers($term);

            return response()->json($customers);
        }

    public function addInteraction(Request $request, Ticket $ticket)
        {
            // Validar el comentario
            $request->validate([
                'comment' => 'required|string|max:1000',  // Validación del comentario
            ]);

            // Crear la interacción
            $ticket->interactions()->create([
                'comment' => $request->comment,
                'user_id' => auth()->id(),  // Usuario que agrega la interacción
            ]);

            // Redirigir al detalle del ticket con éxito
            return redirect()->route('tickets.show', $ticket)->with('success', 'Interacción guardada correctamente.');
        }
}
