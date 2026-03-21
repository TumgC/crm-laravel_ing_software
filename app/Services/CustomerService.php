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

    public function searchCustomers($term)
    {
        $customers = [
            [
                'id' => 1,
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'dpi' => '1234567890101',
            ],
            [
                'id' => 2,
                'first_name' => 'María',
                'last_name' => 'Gómez',
                'dpi' => '2234567890102',
            ],
            [
                'id' => 3,
                'first_name' => 'Carlos',
                'last_name' => 'López',
                'dpi' => '3234567890103',
            ],
            [
                'id' => 4,
                'first_name' => 'Ana',
                'last_name' => 'Ramírez',
                'dpi' => '4234567890104',
            ],
            [
                'id' => 5,
                'first_name' => 'Luis',
                'last_name' => 'Martínez',
                'dpi' => '5234567890105',
            ],
            [
                'id' => 6,
                'first_name' => 'Sofía',
                'last_name' => 'Hernández',
                'dpi' => '6234567890106',
            ],
            [
                'id' => 7,
                'first_name' => 'Pedro',
                'last_name' => 'Castillo',
                'dpi' => '7234567890107',
            ],
            [
                'id' => 8,
                'first_name' => 'Lucía',
                'last_name' => 'Morales',
                'dpi' => '8234567890108',
            ],
            [
                'id' => 9,
                'first_name' => 'Diego',
                'last_name' => 'Ortega',
                'dpi' => '9234567890109',
            ],
            [
                'id' => 10,
                'first_name' => 'Valeria',
                'last_name' => 'Méndez',
                'dpi' => '1023456789010',
            ],
        ];

        $term = mb_strtolower(trim($term));

        if ($term === '') {
            return [];
        }

        return array_values(array_filter($customers, function ($customer) use ($term) {
            return str_contains(mb_strtolower($customer['first_name']), $term)
                || str_contains(mb_strtolower($customer['last_name']), $term)
                || str_contains(mb_strtolower($customer['dpi']), $term);
        }));
    }
}