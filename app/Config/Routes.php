<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');


$routes->get('/dashboard', 'AdminController::dashboard');

$routes->get('/karyawan', 'Karyawan::index');
$routes->get('/karyawan/izin', 'Karyawan::izin');
$routes->post('/karyawan/kirimIzin', 'Karyawan::kirimIzin');
$routes->get('/karyawan/cuti', 'Karyawan::cuti');
$routes->post('/karyawan/kirimCuti', 'Karyawan::kirimCuti');
$routes->get('/karyawan/riwayat', 'Karyawan::riwayat');
