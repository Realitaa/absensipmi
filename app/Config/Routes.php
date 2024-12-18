<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');


$routes->get('/dashboard', 'AdminController::dashboard');
$routes->get('/karyawan', 'AdminController::karyawan');
$routes->get('/laporan', 'AdminController::laporan');
