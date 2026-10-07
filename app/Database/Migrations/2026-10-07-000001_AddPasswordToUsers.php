<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: AddPasswordToUsers
 *
 * Adds the `password` column to the `users` table for authentication.
 * Uses VARCHAR(255) to store secure password_hash() digests.
 *
 * Run with: php spark migrate
 * Rollback with: php spark migrate:rollback
 */
class AddPasswordToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
                'after'      => 'email',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'password');
    }
}
