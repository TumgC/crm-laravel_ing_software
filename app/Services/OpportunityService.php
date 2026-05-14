<?php

namespace App\Services;

use App\Models\Opportunity;
use App\Models\OpportunityStageHistory;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\InternalNotification;

class OpportunityService
{
    protected CustomerService $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function getStages(bool $includeAll = false): array
    {
        $stages = config('opportunities.stages', []);

        return $includeAll ? array_merge(['Todos'], $stages) : $stages;
    }

    public function getFilteredOpportunities(?string $stage, ?string $term): LengthAwarePaginator
    {
        return Opportunity::query()
            ->byStage($stage)
            ->search($term)
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();
    }

    public function getStageCounts(): array
    {
        $stages = array_fill_keys($this->getStages(), 0);

        $realCounts = Opportunity::selectRaw('stage, COUNT(*) as total')
            ->groupBy('stage')
            ->pluck('total', 'stage')
            ->toArray();

        foreach ($realCounts as $stage => $total) {
            if (array_key_exists($stage, $stages)) {
                $stages[$stage] = $total;
            }
        }

        return $stages;
    }

    public function getTotalOpportunities(): int
    {
        return Opportunity::count();
    }

    public function createOpportunity(array $data, int $createdBy): Opportunity
    {
        $customer = $this->findCustomerById((int) $data['customer_id']);

        if (!$customer) {
            throw new \InvalidArgumentException('El cliente no existe.');
        }

        if (!$this->isCustomerActive($customer)) {
            throw new \InvalidArgumentException('El cliente no está activo.');
        }

        return Opportunity::create([
            'customer_id' => $data['customer_id'],
            'customer_name' => $this->resolveCustomerName($customer),
            'amount' => $data['amount'],
            'estimated_close_date' => $data['estimated_close_date'] ?? null,
            'description' => $data['description'] ?? null,
            'stage' => 'Prospecto',
            'created_by' => $createdBy,
        ]);
    }

    public function updateStage(Opportunity $opportunity, string $newStage, int $changedBy): void
    {
        $oldStage = $opportunity->stage;

        if ($oldStage === $newStage) {
            return;
        }

        OpportunityStageHistory::create([
            'opportunity_id' => $opportunity->id,
            'old_stage' => $oldStage,
            'new_stage' => $newStage,
            'changed_by' => $changedBy,
            'changed_at' => Carbon::now(),
        ]);

        $data = [
            'stage' => $newStage,
        ];

        if ($newStage === 'Cerrado Ganado') {
            $data['closed_at'] = Carbon::now();
            $data['closed_by'] = $changedBy;
            $data['final_amount'] = $opportunity->amount;

            InternalNotification::create([
                'user_id' => $changedBy,
                'opportunity_id' => $opportunity->id,
                'title' => 'Oportunidad cerrada como ganada',
                'message' => 'La oportunidad "' . $opportunity->description . '" del cliente ' . $opportunity->customer_name . ' fue marcada como Cerrado Ganado por un monto de Q' . number_format($opportunity->amount, 2) . '.',
            ]);
        }

        $opportunity->update($data);
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

    protected function isCustomerActive(array $customer): bool
    {
        $status = $customer['status'] ?? $customer['estado'] ?? null;

        if (is_bool($status)) {
            return $status;
        }

        if (is_numeric($status)) {
            return (int) $status === 1;
        }

        if (is_string($status)) {
            $normalized = mb_strtolower(trim($status));
            return in_array($normalized, ['activo', 'activa', 'active', '1', 'true', 'habilitado'], true);
        }

        return false;
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