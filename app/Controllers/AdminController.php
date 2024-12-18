<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Admin',
            'current_page' => 'dashboard',
        ];
        return view('admin/dashboard', $data);
    }

    public function karyawan()
    {
        $data = [
            'title' => 'Karyawan PMI',
            'current_page' => 'karyawan',
        ];
        return view('admin/karyawan', $data);
    }

    public function laporan()
    {
        $data = [
            'title' => 'Laporan Absensi',
            'current_page' => 'laporan',
        ];
        return view('admin/laporan', $data);
    }
}
