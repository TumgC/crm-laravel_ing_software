<?php

namespace App\Services;

use Exception;

class SupportStatusService
{
    public function checkCustomerStatus(int $customerId): array
    {
        // Simulación del módulo Soporte
        // Cliente 4: tiene ticket crítico
        // Cliente 9: falla el servicio
        // Cualquier otro: sin ticket crítico

        if ($customerId === 4) {
            return [
                'success' => true,
                'has_critical_ticket' => true,
                'summary' => [
                    'category' => 'Problema con acceso al sistema',
                    'date' => now()->subDay()->format('d/m/Y h:i A'),
                ],
            ];
        }

        if ($customerId === 9) {
            throw new Exception('El servicio de Soporte no respondió.');
        }

        return [
            'success' => true,
            'has_critical_ticket' => false,
            'summary' => null,
        ];
    }
}