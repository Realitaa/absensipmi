<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CutiModel;
use App\Models\UserModel;
use App\Models\AdminModel;
use App\Models\HadirModel;
use App\Models\SakitModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $modelHadir = new HadirModel();
        $modelSakit = new SakitModel();
        $modelCuti = new CutiModel();
        $modelUser = new UserModel();
        $karyawan_hadir = $modelHadir->select('hadir.id, nama, jabatan, waktu')
            ->join('users', 'user_id = users.id')
            ->where('DATE(waktu)', date('Y-m-d'))
            ->findAll();

        $karyawan_sakit = $modelSakit->select('sakit.id, nama, mulai, selesai, judul, deskripsi, sakit.status, waktu_pengajuan')
            ->join('users', 'user_id = users.id')
            ->where('selesai >=', date('Y-m-d')) // Filter untuk tanggal selesai >= hari ini
            ->findAll();

        $karyawan_cuti = $modelCuti->select('cuti.id, nama, mulai, selesai, judul, deskripsi, cuti.status, waktu_pengajuan')
            ->join('users', 'user_id = users.id')
            ->where('selesai >=', date('Y-m-d')) // Filter untuk tanggal selesai >= hari ini
            ->findAll();

        $karyawan_tanpaKeterangan = $modelUser
            ->select('users.nama, users.jabatan, users.email, users.no_telepon, users.status')
            ->where('NOT EXISTS (
                SELECT 1 FROM sakit 
                WHERE users.id = sakit.user_id 
                AND sakit.mulai <= CURDATE() 
                AND sakit.selesai >= CURDATE()
            )')
            ->where('NOT EXISTS (
                SELECT 1 FROM cuti 
                WHERE users.id = cuti.user_id 
                AND cuti.mulai <= CURDATE() 
                AND cuti.selesai >= CURDATE()
            )')
            ->where('NOT EXISTS (
                SELECT 1 FROM hadir 
                WHERE users.id = hadir.user_id 
                AND DATE(hadir.waktu) = CURDATE()
            )')
            ->findAll();

        $data = [
            'title' => 'Dashboard Admin',
            'current_page' => 'dashboard',
            'karyawan_hadir' => $karyawan_hadir,
            'karyawan_sakit' => $karyawan_sakit,
            'karyawan_cuti' => $karyawan_cuti,
            'karyawan_tanpaKeterangan' => $karyawan_tanpaKeterangan,
        ];
        return view('admin/dashboard', $data);
    }

    public function karyawan()
    {
        $model = new UserModel();
        $users = $model->findAll();
        $data = [
            'title' => 'Karyawan PMI',
            'current_page' => 'karyawan',
            'karyawan' => $users,
        ];
        return view('admin/karyawan', $data);
    }

    public function admin()
    {
        $model = new AdminModel();
        $admins = $model->findAll();
        $data = [
            'title' => 'Karyawan PMI',
            'current_page' => 'admin',
            'admins' => $admins,
        ];
        return view('admin/admin', $data);
    }

    public function laporan()
    {
        $model = new UserModel();
        $users = $model->select('users.foto, users.nama, users.email, users.no_telepon, users.jabatan, users.status, 
        hadir.id AS hadir_id, sakit.id AS sakit_id, cuti.id AS cuti_id, tanpaketerangan.id as tanpaketerangan_id,
        DATE_FORMAT(waktu, "%d-%m-%Y") AS tanggal, TIME(waktu) AS jam')
    ->join('hadir', 'hadir.user_id = users.id', 'left')
    ->join('sakit', 'sakit.user_id = users.id', 'left')
    ->join('cuti', 'cuti.user_id = users.id', 'left')
    ->join('tanpaketerangan', 'tanpaketerangan.user_id = users.id', 'left')
    ->findAll();


        $data = [
            'title' => 'Laporan Absensi',
            'current_page' => 'laporan',
            'karyawan' => $users,
        ];
        return view('admin/laporan', $data);
    }
}
