<?php

namespace App\Controllers;

/**
 * Customers Controller
 * 
 * Handles the Customer Accounts page (/customers).
 * 
 * Instead of using a database, we use a static PHP array
 * as our temporary data source — this is what the activity requires.
 * 
 * Route → Controller → View:
 *   GET /customers → Customers::index() → views/customers/index.php
 */
class Customers extends BaseController
{
    /**
     * Customer Accounts Page
     * URL: /customers
     * 
     * Steps:
     * 1. Define a static array of customer records.
     * 2. Put it inside $data so we can pass it to the view.
     * 3. Return the view with the $data array.
     * 
     * In the view, $customers becomes available because CodeIgniter
     * automatically "extracts" the $data array into variables.
     */
    public function index(): string
    {
        // ----------------------------------------------------------------
        // Static PHP array — temporary replacement for a database table.
        // Each item in the array is one customer record (associative array).
        // ----------------------------------------------------------------
        $customers = [
            [
                'full_name' => 'Maria Santos',
                'email'     => 'maria.santos@email.com',
                'phone'     => '+63 912 345 6789',
            ],
            [
                'full_name' => 'Jose Reyes',
                'email'     => 'jose.reyes@email.com',
                'phone'     => '+63 917 234 5678',
            ],
            [
                'full_name' => 'Ana Dela Cruz',
                'email'     => 'ana.delacruz@email.com',
                'phone'     => '+63 918 876 5432',
            ],
            [
                'full_name' => 'Ramon Villanueva',
                'email'     => 'ramon.villanueva@email.com',
                'phone'     => '+63 920 111 2233',
            ],
            [
                'full_name' => 'Liza Mercado',
                'email'     => 'liza.mercado@email.com',
                'phone'     => '+63 935 987 6543',
            ],
            [
                'full_name' => 'Carlo Bautista',
                'email'     => 'carlo.bautista@email.com',
                'phone'     => '+63 999 555 4321',
            ],
        ];

        // Pass the array to the view inside the $data array.
        // In the view, use $customers to access it.
        $data = [
            'title'     => 'Customer Accounts',
            'customers' => $customers,
        ];

        return view('customers/index', $data);
    }
}
