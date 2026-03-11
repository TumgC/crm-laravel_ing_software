<?php

namespace App\Services;

class CustomerService
{
    public function validateCustomer($customerId)
    {
        // Simulación: clientes válidos del 1 al 10
        if ($customerId >= 1 && $customerId <= 10) {
            return [
                'valid' => true,
                'name' => 'Cliente Demo ' . $customerId
            ];
        }

        return [
            'valid' => false
        ];
    }
}
