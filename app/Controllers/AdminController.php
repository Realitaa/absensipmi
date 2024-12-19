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

        $karyawan_sakit = $modelSakit->select('sakit.id, nama, waktu, judul, deskripsi, sakit.status, waktu_pengajuan')
            ->join('users', 'user_id = users.id')
            ->where('DATE(waktu)', date('Y-m-d'))
            ->findAll();

        $karyawan_cuti = $modelCuti->select('cuti.id, nama, waktu, judul, deskripsi, cuti.status, waktu_pengajuan')
            ->join('users', 'user_id = users.id')
            ->where('DATE(waktu)', date('Y-m-d'))
            ->findAll();

        $karyawan_tanpaKeterangan = $modelUser
            ->select('users.nama, users.jabatan, users.email, users.no_telepon, users.status')
            ->where('NOT EXISTS (
                SELECT 1 FROM sakit 
                WHERE users.id = sakit.user_id 
                AND DATE(sakit.waktu) = CURDATE()
            )')
            ->where('NOT EXISTS (
                SELECT 1 FROM cuti 
                WHERE users.id = cuti.user_id 
                AND DATE(cuti.waktu) = CURDATE()
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
    
    // Kirimkan data ke view
    $data = [
        'title' => 'Laporan Absensi',
        'current_page' => 'laporan',

    ];

    return view('admin/laporan', $data);
}



    // Menampilkan halaman tambah karyawan
    public function addKaryawan()
    {
        return view('addKaryawan'); // Pastikan file addKaryawan.php ada di Views
    }

    // Memproses data form tambah karyawan
    public function saveKaryawan()
    {
        // Validasi data form
        $validation = $this->validate([
            'nama' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'telepon' => 'required|numeric|min_length[10]|max_length[15]',
            'jabatan' => 'required'
        ]);

        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman tambah karyawan
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Simpan data ke database (dummy code, sesuaikan dengan model Anda)
        $data = [
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'jabatan' => $this->request->getPost('jabatan')
        ];

        // Simpan data ke database (gunakan model)
        // Contoh: $this->karyawanModel->save($data);

        // Redirect dengan pesan sukses
        return redirect()->to('admin/addKaryawan')->with('success', 'Karyawan berhasil ditambahkan!');
    }
}
