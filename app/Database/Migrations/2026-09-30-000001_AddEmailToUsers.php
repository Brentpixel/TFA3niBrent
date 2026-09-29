<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: AddEmailToUsers
 *
 * Adds an optional `email` column to the `users` table.
 * The column is placed after `full_name` to keep field ordering logical.
 */
class AddEmailToUsers extends Migration
{
    public function up(): void
    {
        $this->forge->addColumn('users', [
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'full_name',
            ],
        ]);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'email');
    }
}
