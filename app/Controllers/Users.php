<?php

namespace App\Controllers;

/**
 * Users Controller
 * 
 * Handles the User Accounts page (/users).
 * 
 * Just like the Customers controller, we use a static PHP array
 * instead of a database — as required by the activity.
 * 
 * Route → Controller → View:
 *   GET /users → Users::index() → views/users/index.php
 */
class Users extends BaseController
{
    /**
     * User Accounts Page
     * URL: /users
     * 
     * Steps:
     * 1. Define a static array of user/staff records.
     * 2. Put it inside $data so we can pass it to the view.
     * 3. Return the view with the $data array.
     */
    public function index(): string
    {
        // ----------------------------------------------------------------
        // Static PHP array — each item represents a system user/staff.
        // ----------------------------------------------------------------
        $users = [
            [
                'username'  => 'admin01',
                'full_name' => 'Brent Verdera',
                'role'      => 'Admin',
            ],
            [
                'username'  => 'cashier_01',
                'full_name' => 'Cyryl Suficiencia',
                'role'      => 'Cashier',
            ],
            [
                'username'  => 'manager_jo',
                'full_name' => 'Kenji Cruz Tigas',
                'role'      => 'Manager',
            ],
            [
                'username'  => 'staff_ann',
                'full_name' => 'Kenneth Ducusin',
                'role'      => 'Staff',
            ],
            [
                'username'  => 'inv_keeper',
                'full_name' => 'James Zuniga',
                'role'      => 'Inventory',
            ],
            [
                'username'  => 'cashier_02',
                'full_name' => 'Carlo Salorio',
                'role'      => 'Cashier',
            ],
        ];

        // Pass the array to the view.
        $data = [
            'title' => 'User Accounts',
            'users' => $users,
        ];

        return view('users/index', $data);
    }
}
