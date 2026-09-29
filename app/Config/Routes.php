<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ── IT0049 TFA3: Forms, Validation, and File Upload ──────────────────────────
//
//  Core pages (TFA1 / TFA2)
//  /          → Home::index()
//  /tasks     → Tasks::index()
//  /profile   → Profile::index()
//  /about     → About::index()
//
//  Customers (TFA3)
//  GET  /customers            → Customers::index()
//  GET  /customers/new        → Customers::newForm()
//  POST /customers/new        → Customers::create()
//  GET  /customers/(:num)/edit→ Customers::edit($id)
//  POST /customers/(:num)/edit→ Customers::update($id)
//
//  Users (TFA3)
//  GET  /users                → Users::index()
//  GET  /users/new            → Users::newForm()
//  POST /users/new            → Users::create()
//  GET  /users/(:num)/edit    → Users::edit($id)
//  POST /users/(:num)/edit    → Users::update($id)
// ─────────────────────────────────────────────────────────────────────────────

// ── Core routes ───────────────────────────────────────────────────────────────
$routes->get('/',        'Home::index');
$routes->get('/tasks',   'Tasks::index');
$routes->get('/profile', 'Profile::index');
$routes->get('/about',   'About::index');

// ── Customers ─────────────────────────────────────────────────────────────────
$routes->get( '/customers',             'Customers::index');
$routes->get( '/customers/new',         'Customers::newForm');
$routes->post('/customers/new',         'Customers::create');
$routes->get( '/customers/(:num)/edit', 'Customers::edit/$1');
$routes->post('/customers/(:num)/edit', 'Customers::update/$1');

// ── Users ─────────────────────────────────────────────────────────────────────
$routes->get( '/users',             'Users::index');
$routes->get( '/users/new',         'Users::newForm');
$routes->post('/users/new',         'Users::create');
$routes->get( '/users/(:num)/edit', 'Users::edit/$1');
$routes->post('/users/(:num)/edit', 'Users::update/$1');
