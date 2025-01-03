<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Group Route Employee
// Authentication
$routes->view('absensipmi', 'login');
$routes->post('absensipmi/login', 'AuthController::login');

$routes->group('absensipmi', ['filter' => 'auth:karyawan'], static function ($routes) {
    $routes->get('home', 'KaryawanController::index');
    $routes->get('kehadiran', 'KaryawanController::kehadiran');
    $routes->post('kehadiran/hadir', 'KaryawanController::hadir');
    $routes->post('ketidakhadiran', 'KaryawanController::ketidakhadiran');
    $routes->get('karyawan/(:alphanum)', 'KaryawanController::karyawan/$1');
    $routes->post('karyawan/update/me', 'KaryawanController::updateKaryawan/$1'); 
});

// Group Route Administrator
// Authentication
$routes->view('absensipmi/administrator', 'admin/login');
$routes->post('absensipmi/administrator/login', 'AuthController::AdminValidation');
$routes->group('absensipmi/administrator', ['filter' => 'auth:admin'], function ($routes) {
    // Navbar
    $routes->get('server-time', 'TimeController::getServerTime');
    $routes->get('ketidakhadiranNotif', 'AdminController::ketidakhadiranNotif');

    // Dashboard
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('getDashboardData', 'AdminController::getDashboardData');
    $routes->get('getTableData/(:segment)', 'AdminController::getTableData/$1');

    // Absensi
    $routes->get('absensi', 'AdminController::absensi');
    $routes->get('absensi/fetchqr', 'AdminController::generateBarcodeAPI');
    $routes->get('absensi/getAttendanceLogs', 'AdminController::getLogs');

    // Menu Karyawan
    $routes->get('karyawan/detail/(:num)', 'KaryawanController::karyawan/$1');
    // Menu Karyawan : Tambah Karyawan
    $routes->get('karyawan', 'AdminController::karyawan');
    $routes->get('karyawan/add', 'AdminController::addKaryawan');
    $routes->post('karyawan/save', 'AdminController::saveKaryawan');
    // Menu Karyawan : Edit Karyawan
    $routes->get('karyawan/edit/(:num)', 'AdminController::editKaryawan/$1');
    $routes->post('karyawan/delete/(:num)', 'AdminController::deleteKaryawan/$1');
    $routes->post('karyawan/status/(:num)', 'AdminController::statusKaryawan/$1');
    $routes->post('karyawan/update/(:num)', 'AdminController::updateKaryawan/$1');
    $routes->post('karyawan/reset/password/(:num)', 'AdminController::resetPassword/$1');
    $routes->post('karyawan/reset/avatar', 'AdminController::resetAvatar');
    $routes->post('karyawan/absensi/delete', 'AdminController::deleteAbsensi');

    // Menu Admin
    $routes->get('admin', 'AdminController::admin');
    $routes->get('admin/add', 'AdminController::addAdmin');
    $routes->post('admin/save', 'AdminController::saveAdmin');
    // Menu Admin : Edit Admin
    $routes->get('admin/edit/(:num)', 'AdminController::editAdmin/$1');
    $routes->post('admin/delete/(:num)', 'AdminController::deleteAdmin/$1');
    $routes->post('admin/status/(:num)', 'AdminController::statusAdmin/$1');
    $routes->post('admin/update/(:num)', 'AdminController::updateAdmin/$1');
    $routes->post('admin/reset/password/(:num)', 'AdminController::resetAdmin/$1');

    //Menu Ketidakhadiran
    $routes->get('ketidakhadiran', 'AdminController::ketidakhadiran');
    $routes->post('ketidakhadiran/accept/(:num)', 'AdminController::acceptAbsensi/$1');
    $routes->post('ketidakhadiran/reject/(:num)', 'AdminController::rejectAbsensi/$1');

    // Menu Laporan
    $routes->get('laporan', 'AdminController::laporan');
    // Menu Laporan : AJAX
    $routes->get('laporan/getHarianData', 'AdminController::getHarianData');
    $routes->get('laporan/getBulananData', 'AdminController::getBulananData');
});

// Mendapatkan data Tabel Laporan absensi Bulanan Karyawan
$routes->get('absensipmi/laporan/getUserBulananData', 'KaryawanController::getUserBulananData');

//Rincian Absensi
$routes->get('absensipmi/absensi/(:num)', 'KaryawanController::rincianKetidakhadiran/$1');

// Logout Administrator dan Karyawan
$routes->get('absensipmi/logout/(:segment)', 'AuthController::logout/$1'); 