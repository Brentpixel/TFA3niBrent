<?php

namespace App\Controllers;

use App\Models\TaskModel;

/**
 * Tasks Controller
 *
 * Route: GET /tasks
 *
 * Displays all tasks ordered by date ascending.
 * Database query lives in TaskModel::getAllOrderedByDate().
 */
class Tasks extends BaseController
{
    public function index(): string
    {
        $taskModel = new TaskModel();
        $tasks     = $taskModel->getAllOrderedByDate();

        return view('tasks/index', [
            'tasks'     => $tasks,
            'pageTitle' => 'All Tasks',
        ]);
    }
}
