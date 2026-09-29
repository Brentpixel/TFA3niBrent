<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Profile Controller
 *
 * Route: GET /profile
 *
 * Fetches and displays the single demo user.
 * Database query lives in UserModel::getDemoUser().
 */
class Profile extends BaseController
{
    public function index(): string
    {
        $userModel = new UserModel();
        $user      = $userModel->getDemoUser();

        return view('profile/index', [
            'user'      => $user,
            'pageTitle' => 'Developer Profile',
        ]);
    }
}
