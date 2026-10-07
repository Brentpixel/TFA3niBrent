<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * UserPasswordSeeder
 *
 * Populates secure password_hash() digests for existing and demo users.
 * Demo credentials:
 *   Username: bverdera  | Password: Password123!
 *   Username: boykanin  | Password: Password123!
 *
 * Run with: php spark db:seed UserPasswordSeeder
 */
class UserPasswordSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = 'Password123!';
        $hashed = password_hash($defaultPassword, PASSWORD_DEFAULT);

        // Update all existing users with the hashed demo password if currently empty or null
        $this->db->table('users')
            ->where('password IS NULL')
            ->orWhere('password', '')
            ->update(['password' => $hashed]);

        // Ensure primary demo user 'bverdera' exists
        $demoUser = $this->db->table('users')->where('username', 'bverdera')->get()->getRowArray();

        if (! $demoUser) {
            $this->db->table('users')->insert([
                'username'   => 'bverdera',
                'full_name'  => 'Brent Verdera',
                'email'      => 'brent.verdera@student.edu.ph',
                'password'   => $hashed,
                'avatar'     => null,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $this->db->table('users')
                ->where('username', 'bverdera')
                ->update(['password' => $hashed]);
        }
    }
}
