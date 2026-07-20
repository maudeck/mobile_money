<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');

$routes->get('/login', 'AuthController::index');
$routes->post('/login/auth', 'AuthController::auth');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/admin/dashboard', 'AuthController::adminDashboard');
$routes->get('/client/dashboard', 'AuthController::clientDashboard');
