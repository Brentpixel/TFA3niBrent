<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeds the `users` table with exactly one demo user.
 * Uses INSERT IGNORE to prevent duplicates on re-run.
 *
 * Run with: php spark db:seed UserSeeder
 */
class UserSeeder extends Seeder
{
    public function run(): void
    {
        // INSERT IGNORE skips the row silently if the unique `username` already exists.
        $this->db->query("
            INSERT IGNORE INTO users (id, username, full_name, email, created_at)
            VALUES (1, 'bverdera', 'Brent Verdera', 'brent.verdera@student.edu.ph', '2026-09-01 08:00:00')
        ");
    }
}
