<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds the `avatar` column to the `users` table.
 * Stores only the filename (e.g., "abc123.jpg"); the full path is
 * resolved at runtime relative to public/uploads/avatars/.
 *
 * Run with: php spark migrate
 * Reverse with: php spark migrate:rollback
 */
class AddAvatarToUsers extends Migration
{
    public function up(): void
    {
        $fields = [
            'avatar' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'default'    => null,
                'after'      => 'full_name',
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down(): void
    {
        $this->forge->dropColumn('users', 'avatar');
    }
}
