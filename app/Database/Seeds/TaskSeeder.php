<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeds the `tasks` table with 8+ realistic tasks spread across 3+ dates.
 *
 * Date strategy (Asia/Manila, UTC+8):
 *   - 2026-09-27  →  2 tasks  (two days ago)
 *   - 2026-09-28  →  3 tasks  (yesterday)
 *   - 2026-09-29  →  3 tasks  (today — the current date when this was written)
 *
 * If you run the project on a later date, today's filter on the home page
 * will return 0 rows.  Follow the README instructions to insert a new
 * task with today's date to demonstrate the home page filter.
 *
 * Uses INSERT IGNORE so re-running the seeder never creates duplicate rows.
 *
 * Run with: php spark db:seed TaskSeeder
 */
class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            // ── 2026-09-27  (two days ago) ───────────────────────────────────
            [
                'id'         => 1,
                'title'      => 'Review Chapter 3 – Database Normalization',
                'status'     => 'done',
                'task_date'  => '2026-09-27',
                'created_at' => '2026-09-27 07:30:00',
            ],
            [
                'id'         => 2,
                'title'      => 'Submit Networking Lab Report',
                'status'     => 'done',
                'task_date'  => '2026-09-27',
                'created_at' => '2026-09-27 08:15:00',
            ],

            // ── 2026-09-28  (yesterday) ──────────────────────────────────────
            [
                'id'         => 3,
                'title'      => 'Study for IT0049 Midterm Examination',
                'status'     => 'done',
                'task_date'  => '2026-09-28',
                'created_at' => '2026-09-28 06:45:00',
            ],
            [
                'id'         => 4,
                'title'      => 'Push CodeIgniter activity to GitHub',
                'status'     => 'done',
                'task_date'  => '2026-09-28',
                'created_at' => '2026-09-28 09:00:00',
            ],
            [
                'id'         => 5,
                'title'      => 'Read documentation on MVC architecture',
                'status'     => 'done',
                'task_date'  => '2026-09-28',
                'created_at' => '2026-09-28 11:30:00',
            ],

            // ── 2026-09-29  (today) ──────────────────────────────────────────
            [
                'id'         => 6,
                'title'      => 'Complete Summative Assessment 1 – Tasks for Today App',
                'status'     => 'in-progress',
                'task_date'  => '2026-09-29',
                'created_at' => '2026-09-29 08:00:00',
            ],
            [
                'id'         => 7,
                'title'      => 'Deploy project to a live host and get the public URL',
                'status'     => 'pending',
                'task_date'  => '2026-09-29',
                'created_at' => '2026-09-29 08:05:00',
            ],
            [
                'id'         => 8,
                'title'      => 'Prepare GitHub repository and push all project files',
                'status'     => 'pending',
                'task_date'  => '2026-09-29',
                'created_at' => '2026-09-29 08:10:00',
            ],
        ];

        foreach ($tasks as $task) {
            // INSERT IGNORE skips the row if the primary key already exists.
            $this->db->query(
                "INSERT IGNORE INTO tasks (id, title, status, task_date, created_at)
                 VALUES (?, ?, ?, ?, ?)",
                [
                    $task['id'],
                    $task['title'],
                    $task['status'],
                    $task['task_date'],
                    $task['created_at'],
                ]
            );
        }
    }
}
