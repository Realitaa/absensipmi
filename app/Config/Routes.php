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
    $routes->get('home', 'KaryawanController::index');
    $routes->get('kehadiran', 'KaryawanController::kehadiran');
    $routes->post('kehadiran/hadir', 'KaryawanController::hadir');
    $routes->post('ketidakhadiran', 'KaryawanController::ketidakhadiran');
    $routes->get('karyawan/(:alphanum)', 'KaryawanController::karyawan/$1'); //Dapat diakses admin
    $routes->post('karyawan/update/me', 'KaryawanController::updateKaryawan/$1'); 
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
    $routes->get('absensi', 'AdminController::absensi');
    $routes->get('absensi/fetchqr', 'AdminController::generateBarcodeAPI');
    $routes->get('absensi/getAttendanceLogs', 'AdminController::getLogs');

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
});

$routes->get('logout/(:segment)', 'AuthController::logout/$1'); 