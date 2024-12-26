<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Group Route Employee
// Authentication
$routes->view('/', 'login');
$routes->post('/login', 'AuthController::login');

$routes->group('', ['filter' => 'auth:karyawan'], static function ($routes) {
    $routes->get('home', 'Karyawan::index');
    $routes->get('kehadiran', 'Karyawan::kehadiran');
    $routes->post('ketidakhadiran', 'Karyawan::ketidakhadiran');
    $routes->get('karyawan/(:num)', 'Karyawan::karyawan/$1'); //Dapat diakses admin
    $routes->get('karyawan/me', 'Karyawan::$1'); 
    $routes->get('karyawan/update/me', 'Karyawan::editKaryawan/$1'); 

    $routes->get('logout/(:segment)', 'AuthController::logout/$1'); 
});

// Group Route Administrator
// Authentication
$routes->view('/administrator', 'admin/login');
$routes->post('/administrator/login', 'AuthController::AdminValidation');
$routes->group('administrator', ['filter' => 'auth:admin'], function ($routes) {
    // To get server time
    $routes->get('server-time', 'TimeController::getServerTime');

    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');

    // Menu Karyawan
    // Menu Karyawan : Tambah Karyawan
    $routes->get('karyawan', 'AdminController::karyawan');
    $routes->get('karyawan/add', 'AdminController::addKaryawan');
    $routes->post('karyawan/save', 'AdminController::saveKaryawan');
    // Menu Karyawan : Edit Karyawan
    $routes->get('karyawan/edit/(:num)', 'AdminController::editKaryawan/$1');
    $routes->post('karyawan/delete/(:num)', 'AdminController::deleteKaryawan/$1');
    $routes->post('karyawan/status/(:num)', 'AdminController::statusKaryawan/$1');
    $routes->post('karyawan/update/(:num)', 'AdminController::updateKaryawan/$1');
    $routes->post('karyawan/reset/(:num)', 'AdminController::resetKaryawan/$1');

    // Menu Admin
    $routes->get('admin', 'AdminController::admin');
    $routes->get('admin/add', 'AdminController::addAdmin');
    $routes->post('admin/save', 'AdminController::saveAdmin');
    // Menu Admin : Edit Admin
    $routes->get('admin/edit/(:num)', 'AdminController::editAdmin/$1');
    $routes->post('admin/delete/(:num)', 'AdminController::deleteAdmin/$1');
    $routes->post('admin/status/(:num)', 'AdminController::statusAdmin/$1');
    $routes->post('admin/update/(:num)', 'AdminController::updateAdmin/$1');
    $routes->post('admin/reset/(:num)', 'AdminController::resetAdmin/$1');

    // Menu Laporan
    $routes->get('laporan', 'AdminController::laporan');
    // Menu Laporan : AJAX
    $routes->get('laporan/getHarianData', 'AdminController::getHarianData');
    $routes->get('laporan/getBulananData', 'AdminController::getBulananData');

    $routes->get('logout/(:segment)', 'AuthController::logout/$1');
});
