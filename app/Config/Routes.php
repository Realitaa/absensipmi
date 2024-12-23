<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Routes Autentikasi
$routes->get('/', 'AuthController::login'); 

// Routes Karyawan
// $routes->get('/karyawan', 'Karyawan::index');
// $routes->get('/karyawan/izin', 'Karyawan::izin');
// $routes->post('/karyawan/kirimIzin', 'Karyawan::kirimIzin');
// $routes->get('/karyawan/cuti', 'Karyawan::cuti');
// $routes->post('/karyawan/kirimCuti', 'Karyawan::kirimCuti');
// $routes->get('/karyawan/riwayat', 'Karyawan::riwayat');
// Routes Admin : Dashboard
$routes->get('/dashboard', 'AdminController::dashboard');

// Routes Admin : Menu Karyawan
// Menu Karyawan : Tambah Karyawan
$routes->get('/karyawan', 'AdminController::karyawan');
$routes->get('/karyawan/add', 'AdminController::addKaryawan'); 
$routes->post('/karyawan/save', 'AdminController::saveKaryawan');
// Menu Karyawan : Edit Karyawan
$routes->get('/karyawan/edit/(:num)', 'AdminController::editKaryawan/$1'); 
$routes->post('/karyawan/delete/(:num)', 'AdminController::deleteKaryawan/$1'); 
$routes->post('/karyawan/status/(:num)', 'AdminController::statusKaryawan/$1');
$routes->post('/karyawan/update/(:num)', 'AdminController::updateKaryawan/$1'); 

// Routes Admin : Menu Admin
$routes->get('/admin', 'AdminController::admin');
$routes->get('/admin/add', 'AdminController::addAdmin');

// Routes Admin : Menu Laporan
$routes->get('/laporan', 'AdminController::laporan');
// Menu Laporan : AJAX
$routes->get('/laporan/getHarianData', 'AdminController::getHarianData');
$routes->get('/laporan/getBulananData', 'AdminController::getBulananData');
