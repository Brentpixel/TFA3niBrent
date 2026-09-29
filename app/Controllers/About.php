<?php

namespace App\Controllers;

/**
 * About Controller
 *
 * Route: GET /about
 *
 * Static page — no model needed.
 * Identifies Brent Verdera as the developer.
 */
class About extends BaseController
{
    public function index(): string
    {
        return view('about/index', [
            'pageTitle' => 'About the Developer',
        ]);
    }
}
