<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Landing Page → Pages controller, index() method
$routes->get('/', 'Pages::index');

// About Page → Pages controller, about() method
$routes->get('/about', 'Pages::about');

// Customer Accounts → Customers controller, index() method
$routes->get('/customers', 'Customers::index');

// User Accounts → Users controller, index() method
$routes->get('/users', 'Users::index');
