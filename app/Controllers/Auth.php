<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * Auth Controller
 *
 * Handles user authentication (login, logout, session management).
 * Routes:
 *   GET  /login  → login()        — render login page
 *   POST /login  → processLogin() — validate & authenticate credentials
 *   POST /logout → logout()       — terminate session & redirect
 *   GET  /logout → logout()       — fallback handler
 */
class Auth extends BaseController
{
    /**
     * Display the login form.
     * Redirects to home/dashboard if already authenticated.
     */
    public function login()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('/'));
        }

        return view('auth/login', [
            'pageTitle' => 'Sign In',
            'errors'    => session()->getFlashdata('errors') ?? [],
            'old'       => session()->getFlashdata('old')    ?? [],
            'flash'     => session()->getFlashdata('success'),
            'error'     => session()->getFlashdata('error'),
        ]);
    }

    /**
     * Process login form submission.
     */
    public function processLogin()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to(base_url('/'));
        }

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $messages = [
            'username' => [
                'required' => 'Please enter your username.',
            ],
            'password' => [
                'required' => 'Please enter your password.',
            ],
        ];

        $rawUsername = trim((string) $this->request->getPost('username'));
        $rawPassword = (string) $this->request->getPost('password');

        if (! $this->validate($rules, $messages)) {
            session()->setFlashdata('errors', $this->validator->getErrors());
            // Preserve username only, NEVER preserve password
            session()->setFlashdata('old', ['username' => $rawUsername]);
            return redirect()->to(base_url('/login'));
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $rawUsername)
                          ->orWhere('email', $rawUsername)
                          ->first();

        // Generic error message to prevent username enumeration
        $invalidMsg = 'Invalid username or password.';

        if (! $user || empty($user['password']) || ! password_verify($rawPassword, $user['password'])) {
            session()->setFlashdata('error', $invalidMsg);
            session()->setFlashdata('old', ['username' => $rawUsername]);
            return redirect()->to(base_url('/login'));
        }

        // Regenerate session ID to prevent session fixation
        session()->regenerate();

        // Store essential authenticated session information
        session()->set([
            'user_id'      => (int) $user['id'],
            'username'     => $user['username'],
            'full_name'    => $user['full_name'],
            'is_logged_in' => true,
        ]);

        session()->setFlashdata('success', 'Welcome back, ' . esc($user['full_name']) . '!');
        return redirect()->to(base_url('/'));
    }

    /**
     * Terminate the session and log out the user.
     */
    public function logout()
    {
        $session = session();

        // Clear auth flags
        $session->remove(['user_id', 'username', 'full_name', 'is_logged_in']);
        $session->regenerate(true);
        $session->setFlashdata('success', 'You have been logged out successfully.');

        return redirect()->to(base_url('/login'));
    }
}
