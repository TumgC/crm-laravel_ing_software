<?php

namespace App\Services;

class CustomerService
{
    public function getMockCustomers(): array
    {


        return [
        
        [
                'id' => 1,
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'dpi' => '1234567890101',
                'status' => 'Activo',
            ],
            [
                'id' => 2,
                'first_name' => 'María',
                'last_name' => 'Gómez',
                'dpi' => '2234567890102',
                'status' => 'Activo',
            ],
            [
                'id' => 3,
                'first_name' => 'Carlos',
                'last_name' => 'López',
                'dpi' => '3234567890103',
                'status' => 'Activo',
            ],
            [
                'id' => 4,
                'first_name' => 'Ana',
                'last_name' => 'Ramírez',
                'dpi' => '4234567890104',
                'status' => 'Activo',
            ],
            [
                'id' => 5,
                'first_name' => 'Luis',
                'last_name' => 'Martínez',
                'dpi' => '5234567890105',
                'status' => 'Activo',
            ],
            [
                'id' => 6,
                'first_name' => 'Sofía',
                'last_name' => 'Hernández',
                'dpi' => '6234567890106',
                'status' => 'Activo',
            ],
            [
                'id' => 7,
                'first_name' => 'Pedro',
                'last_name' => 'Castillo',
                'dpi' => '7234567890107',
                'status' => 'Activo',
            ],
            [
                'id' => 8,
                'first_name' => 'Lucía',
                'last_name' => 'Morales',
                'dpi' => '8234567890108',
                'status' => 'Activo',
            ],
            [
                'id' => 9,
                'first_name' => 'Diego',
                'last_name' => 'Ortega',
                'dpi' => '9234567890109',
                'status' => 'Activo',
            ],
            [
                'id' => 10,
                'first_name' => 'Valeria',
                'last_name' => 'Méndez',
                'dpi' => '1023456789010',
                'status' => 'Activo',
            ],
        ];
    }

            public function validateCustomer($customerId): array
            {
                foreach ($this->getMockCustomers() as $customer) {
                    if ((int) $customer['id'] === (int) $customerId) {
                        return [
                            'valid' => true,
                            'customer' => $customer,
                        ];
                    }
                }

                return [
                    'valid' => false,
                    'customer' => null,
                ];
            }

            public function searchCustomers($term): array
            {
                $customers = $this->getMockCustomers();

                $term = mb_strtolower(trim((string) $term));

                if ($term === '') {
                    return [];
                }

                return array_values(array_filter($customers, function ($customer) use ($term) {
                    $fullName = mb_strtolower(trim($customer['first_name'] . ' ' . $customer['last_name']));
                    $firstName = mb_strtolower($customer['first_name']);
                    $lastName = mb_strtolower($customer['last_name']);
                    $dpi = mb_strtolower($customer['dpi']);
                    $id = (string) $customer['id'];

                    return str_contains($fullName, $term)
                        || str_contains($firstName, $term)
                        || str_contains($lastName, $term)
                        || str_contains($dpi, $term)
                        || str_contains($id, $term);
                }));
            }
}