<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAssignment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use InvalidArgumentException;

class TicketService
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function getStatuses(): array
    {
        return config('tickets.statuses', []);
    }

    public function getPriorities(bool $includeAll = false): array
    {
        $priorities = ['Baja', 'Media', 'Alta', 'Crítica'];

        return $includeAll ? array_merge(['Todas'], $priorities) : $priorities;
    }

    public function getAgents()
    {
        return User::orderBy('name')->get(['id', 'name', 'email']);
    }

    public function createTicket(array $data): Ticket
    {
        $customer = $this->findCustomerById((int) $data['customer_id']);

        if (!$customer) {
            throw new InvalidArgumentException('El cliente no existe.');
        }

        return Ticket::create([
            'ticket_number' => $this->generateTicketNumber(),
            'customer_id' => $data['customer_id'],
            'customer_name' => $this->resolveCustomerName($customer),
            'subject' => $data['subject'],
            'description' => $this->buildDescription($data),
            'priority' => $data['priority'],
            'status' => $this->resolveStatus($data),
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);
    }

    public function updateTicket(Ticket $ticket, array $data): bool
    {
        return $ticket->update([
            'status' => $data['status'],
            'assigned_to' => $data['assigned_to'] ?? null,
        ]);
    }

    public function assignTicket(Ticket $ticket, int $assignedTo, ?int $assignedBy): void
    {
        $ticket->update([
            'assigned_to' => $assignedTo,
            'status' => 'Asignado',
        ]);

        TicketAssignment::create([
            'ticket_id' => $ticket->id,
            'assigned_by' => $assignedBy,
            'assigned_to' => $assignedTo,
            'assigned_at' => Carbon::now(),
        ]);
    }

    public function getFilteredTickets(?string $priority, ?string $status, ?string $assignedTo, ?string $term): LengthAwarePaginator
    {
        return Ticket::with('assignee')
            ->byPriority($priority)
            ->byStatus($status)
            ->byAssignee($assignedTo)
            ->search($term)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    }

    public function getStatusCounts(): array
    {
        $statuses = array_fill_keys($this->getStatuses(), 0);

        $realCounts = Ticket::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        foreach ($realCounts as $status => $total) {
            if (array_key_exists($status, $statuses)) {
                $statuses[$status] = $total;
            }
        }

        return $statuses;
    }

    public function getTotalTickets(): int
    {
        return Ticket::count();
    }

    protected function generateTicketNumber(): string
    {
        $nextId = (Ticket::max('id') ?? 0) + 1;

        return 'TCK-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT);
    }

    protected function buildDescription(array $data): string
    {
        if (!empty($data['frequent_problem'])) {
            return 'El cliente reporta el siguiente problema: ' . $data['frequent_problem'];
        }

        return $data['description'];
    }

    protected function resolveStatus(array $data): string
    {
        if (!empty($data['assigned_to'])) {
            return 'Asignado';
        }

        return $data['status'];
    }

    protected function findCustomerById(int $customerId): ?array
    {
        $customers = $this->customerService->searchCustomers((string) $customerId);

        if (!is_array($customers)) {
            return null;
        }

        foreach ($customers as $customer) {
            $id = $customer['id'] ?? $customer['customer_id'] ?? null;

            if ((int) $id === $customerId) {
                return $customer;
            }
        }

        return null;
    }

    protected function resolveCustomerName(array $customer): string
    {
        return $customer['name']
            ?? $customer['full_name']
            ?? $customer['customer_name']
            ?? $customer['nombre']
            ?? trim(($customer['first_name'] ?? '') . ' ' . ($customer['last_name'] ?? ''));
    }
}