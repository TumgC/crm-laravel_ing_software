<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddTicketInteractionRequest;
use App\Http\Requests\AssignTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Ticket;
use App\Models\User;
use App\Services\CustomerService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use InvalidArgumentException;
class TicketController extends Controller
{
    protected CustomerService $customerService;
    protected TicketService $ticketService;

    public function __construct(CustomerService $customerService, TicketService $ticketService)
    {
        $this->customerService = $customerService;
        $this->ticketService = $ticketService;
    }

  public function create()
{
    $statuses = $this->ticketService->getStatuses();
    $priorities = $this->ticketService->getPriorities();
    $agents = $this->ticketService->getAgents();

    return view('tickets.create', compact('statuses', 'priorities', 'agents'));
}


public function store(StoreTicketRequest $request)
{
    try {
        $this->ticketService->createTicket($request->validated());
    } catch (InvalidArgumentException $e) {
        return back()->withErrors([
            'customer_id' => $e->getMessage(),
        ])->withInput();
    }

    return redirect()
        ->route('tickets.index')
        ->with('success', 'Ticket creado correctamente.');
}
   public function index(Request $request)
{
    $priority = $request->query('priority');
    $status = $request->query('status');
    $assignedTo = $request->query('assigned_to');
    $q = $request->query('q');

    $tickets = $this->ticketService->getFilteredTickets(
        $priority,
        $status,
        $assignedTo,
        $q
    );

    $statuses = $this->ticketService->getStatuses();
    $priorities = $this->ticketService->getPriorities(true);
    $agents = $this->ticketService->getAgents();
    $statusCounts = $this->ticketService->getStatusCounts();
    $totalTickets = $this->ticketService->getTotalTickets();

    return view('tickets.index', compact(
        'tickets',
        'statuses',
        'priorities',
        'agents',
        'priority',
        'status',
        'assignedTo',
        'q',
        'statusCounts',
        'totalTickets'
    ));
}

    public function assignForm(Ticket $ticket)
    {
        $agents = User::orderBy('name')->get();

        return view('tickets.assign', compact('ticket', 'agents'));
    }

    public function assignStore(AssignTicketRequest $request, Ticket $ticket)
    {
        $this->ticketService->assignTicket(
            $ticket,
            (int) $request->validated()['assigned_to'],
            auth()->id()
        );

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket asignado correctamente.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['interactions', 'assignee']);

        $agents = User::orderBy('name')->get();
        $statuses = $this->ticketService->getStatuses();

        return view('tickets.show', compact('ticket', 'agents', 'statuses'));
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $this->ticketService->updateTicket($ticket, $request->validated());

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Ticket actualizado correctamente.');
    }

    public function searchCustomers(Request $request)
    {
        $term = $request->get('term', '');
        $customers = $this->customerService->searchCustomers($term);

        return response()->json($customers);
    }

    public function addInteraction(AddTicketInteractionRequest $request, Ticket $ticket)
    {
        $ticket->interactions()->create([
            'comment' => $request->validated()['comment'],
            'user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('tickets.show', $ticket)
            ->with('success', 'Interacción guardada correctamente.');
    }
}