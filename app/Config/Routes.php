<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── IT0049 TFA4: Who's Allowed In? Sessions and Authentication ───────────────
//
//  Public routes:
//  /          → Home::index()
//  /tasks     → Tasks::index()
//  /profile   → Profile::index()
//  /about     → About::index()
//  /login     → Auth::login() (GET) | Auth::processLogin() (POST)
//  /logout    → Auth::logout() (POST / GET fallback)
//
//  Protected routes (enforced by AuthFilter):
//  Customers: /customers, /customers/new, /customers/(:num)/edit
//  Users:     /users, /users/new, /users/(:num)/edit
// ─────────────────────────────────────────────────────────────────────────────

// ── Public core routes ───────────────────────────────────────────────────────
$routes->get('/',        'Home::index');
$routes->get('/tasks',   'Tasks::index');
$routes->get('/profile', 'Profile::index');
$routes->get('/about',   'About::index');

// ── Authentication routes ───────────────────────────────────────────────────
$routes->get('/login',   'Auth::login');
$routes->post('/login',  'Auth::processLogin');
$routes->post('/logout', 'Auth::logout');
$routes->get('/logout',  'Auth::logout');

// ── Protected routes (Requires AuthFilter) ──────────────────────────────────
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    // Customers (CRUD)
    $routes->get( '/customers',             'Customers::index');
    $routes->get( '/customers/new',         'Customers::newForm');
    $routes->post('/customers/new',         'Customers::create');
    $routes->get( '/customers/(:num)/edit', 'Customers::edit/$1');
    $routes->post('/customers/(:num)/edit', 'Customers::update/$1');

    // Users (CRUD)
    $routes->get( '/users',             'Users::index');
    $routes->get( '/users/new',         'Users::newForm');
    $routes->post('/users/new',         'Users::create');
    $routes->get( '/users/(:num)/edit', 'Users::edit/$1');
    $routes->post('/users/(:num)/edit', 'Users::update/$1');
});
