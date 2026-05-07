<?php

namespace App\Services;

use App\Models\Opportunity;
use Exception;

class OpportunityContextService
{
    public function getCommercialStatusByCustomer(int $customerId): array
    {
        // Simulación de fallo del servicio de Ventas
        if ($customerId === 9) {
            throw new Exception('El servicio de Ventas no respondió.');
        }

        $opportunity = Opportunity::where('customer_id', $customerId)
            ->orderByDesc('created_at')
            ->first();

        if (!$opportunity) {
            return [
                'success' => true,
                'has_opportunity' => false,
                'stage' => null,
                'reference' => 'Sin oportunidad activa',
            ];
        }

        return [
            'success' => true,
            'has_opportunity' => true,
            'stage' => $opportunity->stage,
            'reference' => '#' . $opportunity->id . ' - ' . ($opportunity->description ?? 'Oportunidad sin descripción'),
        ];
    }
}