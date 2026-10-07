<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeds the `users` table with the demo user.
 *
 * Run with: php spark db:seed UserSeeder
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $hashed = password_hash('Password123!', PASSWORD_DEFAULT);

        // Check if demo user exists
        $user = $this->db->table('users')->where('username', 'bverdera')->get()->getRowArray();
        if (! $user) {
            $this->db->table('users')->insert([
                'username'   => 'bverdera',
                'full_name'  => 'Brent Verdera',
                'email'      => 'brent.verdera@student.edu.ph',
                'password'   => $hashed,
                'avatar'     => null,
                'created_at' => '2026-09-01 08:00:00',
            ]);
        } else {
            $this->db->table('users')->where('username', 'bverdera')->update([
                'password' => $hashed,
            ]);
        }
    }
}
