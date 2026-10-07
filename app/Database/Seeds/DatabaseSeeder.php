<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Master seeder — sets up users, passwords, and tasks.
 * Run with: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('UserSeeder');
        $this->call('UserPasswordSeeder');
        $this->call('TaskSeeder');
    }
}
