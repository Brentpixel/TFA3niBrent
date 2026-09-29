<?php

namespace App\Controllers;

use App\Models\TaskModel;

/**
 * Home Controller
 *
 * Route: GET /
 *
 * Displays only the tasks whose task_date equals today's date
 * in the Asia/Manila timezone (UTC+8).
 *
 * Database query logic lives in TaskModel::getTasksByDate().
 * This controller just resolves today's date, fetches data, and passes
 * it to the view — no raw SQL here.
 */
class Home extends BaseController
{
    public function index(): string
    {
        // ── Resolve "today" in Asia/Manila (UTC+8) ───────────────────────────
        // date_default_timezone_set() is called once in the bootstrap so we
        // call it here as a safety net in case the environment differs.
        date_default_timezone_set('Asia/Manila');
        $today = date('Y-m-d'); // calculated at runtime, never hardcoded

        // ── Fetch tasks from the model ────────────────────────────────────────
        $taskModel  = new TaskModel();
        $todayTasks = $taskModel->getTasksByDate($today);

        // ── Pass data to the view ─────────────────────────────────────────────
        return view('home/index', [
            'today'      => $today,                        // e.g. "2026-09-29"
            'todayLabel' => date('F j, Y'),                // e.g. "September 29, 2026"
            'tasks'      => $todayTasks,
            'pageTitle'  => 'Tasks for Today',
        ]);
    }
}
