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
$routes->get('/managekaryawan', 'AdminController::karyawan');
$routes->get('/admin', 'AdminController::admin');
$routes->get('/laporan', 'AdminController::laporan');
$routes->get('/addKaryawan', 'AdminController::addKaryawan'); // Menampilkan halaman tambah karyawan
$routes->post('admin/addKaryawan', 'AdminController::saveKaryawan'); // Memproses data form tambah karyawan
$routes->get('laporan/getHarianData', 'AdminController::getHarianData');
$routes->get('laporan/laporanBulanan', 'AdminController::laporanBulanan');
$routes->get('admin/editKaryawan/(:num)', 'AdminController::editKaryawan/$1'); // Menampilkan form edit
$routes->post('admin/updateKaryawan/(:num)', 'AdminController::updateKaryawan/$1'); // Memproses data update
