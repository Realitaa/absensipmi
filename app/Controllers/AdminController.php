<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\AdminModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    public function dashboard()
    {
        $modelUser = new UserModel();
        $modelAbsensi = new AbsensiModel(); // Pastikan model ini ada
        $today = date('Y-m-d'); // Format tanggal untuk database

        $karyawan_hadir = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, TIME(absensi.created_at) AS waktu')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Hadir')
            ->where('tanggal', $today)
            ->findAll();

        $karyawan_sakit = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, s.judul, s.deskripsi, s.status, absensi.created_at AS waktu')
            ->join('sakit s', 's.absensi_id = absensi.id')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Sakit')
            ->where('tanggal', $today)
            ->findAll();

        $karyawan_cuti = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, c.judul, c.deskripsi, c.status, absensi.created_at AS waktu')
            ->join('cuti c', 'absensi.id = c.absensi_id')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Cuti')
            ->where('tanggal', $today)
            ->findAll();

        $karyawan_tanpaKeterangan = $modelUser
            ->select('users.id AS kID, users.nama, users.jabatan, users.status, users.email, users.no_telepon')
            ->where('NOT EXISTS (
            SELECT 1 FROM absensi
            WHERE users.id = absensi.user_id 
            AND absensi.tanggal = CURDATE()
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
    $modelAbsensi = new AbsensiModel();
    $min_date = $modelAbsensi->select("DATE_FORMAT(MIN(tanggal), '%d-%m-%Y') AS min_date")->first()['min_date'];
    $max_date = $modelAbsensi->select("DATE_FORMAT(MAX(tanggal), '%d-%m-%Y') AS max_date")->first()['max_date'];
    $min_month = $modelAbsensi->select("DATE_FORMAT(MIN(tanggal), '%m-%Y') AS min_date")->first()['min_date'];
    $max_month = $modelAbsensi->select("DATE_FORMAT(MAX(tanggal), '%m-%Y') AS max_date")->first()['max_date'];

    $data = [
        'title' => 'Laporan Absensi',
        'current_page' => 'laporan',
        'min_date' => $min_date,
        'max_date' => $max_date,
        'min_month' => $min_month,
        'max_month' => $max_month,
    ];

        return view('admin/laporan', $data);
    }

    public function getHarianData()
    {
        $modelAbsensi = new AbsensiModel();

        // Ambil tanggal yang dikirimkan (dari parameter request atau default ke hari sebelumnya)
        $tanggal = $this->request->getVar('tanggal') ?? date('d-m-Y', strtotime('-1 day'));
        $harian = $modelAbsensi->select('u.nama, u.jabatan, u.status, absensi.tipe AS kehadiran, DATE_FORMAT(absensi.tanggal, "%d-%m-%Y") AS tanggal, TIME(absensi.created_at) AS jam')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('DATE_FORMAT(absensi.tanggal, "%d-%m-%Y")', $tanggal)
            ->findAll();

        return $this->response->setJSON($harian);
    }
    
    public function getBulananData()
{
    $modelAbsensi = new AbsensiModel();

    // Ambil bulan dari parameter request
    $bulan = $this->request->getVar('bulan') ?? date('m-Y', strtotime('-1 day'));
    
    // Query untuk mengambil data absensi
    $bulanan = $modelAbsensi
        ->select('u.id AS user_id, u.nama, absensi.tipe, absensi.tanggal')
        ->join('users u', 'absensi.user_id = u.id')
        ->where('DATE_FORMAT(absensi.tanggal, "%m-%Y")', $bulan) // Filter berdasarkan bulan
        ->findAll();

    // Format data untuk dikirimkan sebagai JSON
    $result = [];
    foreach ($bulanan as $row) {
        $userId = $row['user_id'];
        $tanggal = (int)date('d', strtotime($row['tanggal']));

        if (!isset($result[$userId])) {
            $result[$userId] = [
                'nama' => $row['nama'],
                'absensi' => array_fill(1, 30, ''), // Inisialisasi dengan tanggal kosong
                'summary' => ['H' => 0, 'S' => 0, 'C' => 0, 'TK' => 0],
            ];
        }

        // Isi data absensi dan summary
        $result[$userId]['absensi'][$tanggal] = $row['tipe'];
        if (isset($result[$userId]['summary'][$row['tipe']])) {
            $result[$userId]['summary'][$row['tipe']]++;
        }
    }

    return $this->response->setJSON(array_values($result));
}


    // Menampilkan halaman tambah karyawan
    public function addKaryawan()
    {
        $data = [
            'title' => 'Tambah Karyawan',
            'current_page' => 'karyawan',
        ];
        return view('admin/addKaryawan', $data              ); // Pastikan file addKaryawan.php ada di Views
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

    // Menampilkan halaman edit karyawan
    public function editKaryawan($id)
    {
        // Ambil data karyawan berdasarkan ID (gunakan model)
        // Contoh: $karyawan = $this->karyawanModel->find($id);
        $karyawan = [
            'id' => $id,
            'nama' => 'John Doe',
            'email' => 'johndoe@example.com',
            'telepon' => '081234567890',
            'jabatan' => 'Staff'
        ];

        // Kirim data ke view
        return view('editKaryawan', ['karyawan' => $karyawan]);
    }

    // Memproses data update karyawan
    public function updateKaryawan($id)
    {
        // Validasi data form
        $validation = $this->validate([
            'nama' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'telepon' => 'required|numeric|min_length[10]|max_length[15]',
            'jabatan' => 'required'
        ]);

        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman edit
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'id' => $id,
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'jabatan' => $this->request->getPost('jabatan')
        ];

        // Update data ke database (dummy code, sesuaikan dengan model Anda)
        // Contoh: $this->karyawanModel->update($id, $data);

        // Redirect dengan pesan sukses
        return redirect()->to('admin/karyawanList')->with('success', 'Data karyawan berhasil diperbarui!');
    }
}
