<?php

namespace App\Controllers;

/**
 * Pages Controller
 * 
 * Handles the landing page (/) and the about page (/about).
 * Route → Controller → View is the CodeIgniter flow:
 *   GET /       → Pages::index()  → views/pages/home.php
 *   GET /about  → Pages::about()  → views/pages/about.php
 */
class Pages extends BaseController
{
    /**
     * Landing Page
     * URL: /
     * 
     * We pass a $title variable to the view so the browser tab shows the right name.
     */
    public function index(): string
    {
        $data = [
            'title' => 'Home',
        ];

        return view('pages/home', $data);
    }

    /**
     * About Page
     * URL: /about
     */
    public function about(): string
    {
        $data = [
            'title' => 'About',
        ];

        return view('pages/about', $data);
    }
}
