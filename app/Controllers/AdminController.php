<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\AdminModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    // Method untuk Menu Dashboard
    public function dashboard()
    {
        $modelUser = new UserModel();
        $modelAbsensi = new AbsensiModel();
        $today = date('Y-m-d');

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
            AND absensi.tanggal = CURDATE())')
            ->where('users.status = "aktif"')
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

    // Method untuk Menu Karyawan
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

    // Menampilkan halaman tambah karyawan
    public function addKaryawan()
    {
        $data = [
            'title' => 'Tambah Karyawan',
            'current_page' => 'karyawan',
        ];
        return view('admin/addKaryawan', $data); // Pastikan file addKaryawan.php ada di Views
    }

    // Memproses data form tambah karyawan
    public function saveKaryawan()
    {
        $model = new UserModel();
        // Validasi data form
        $validation = $this->validate([
            'nama_lengkap' => 'required|min_length[3]|max_length[50]',
            'nama_pengguna' => 'required|min_length[3]|max_length[50]',
            'email' => 'valid_email',
            'telepon' => 'numeric|min_length[10]|max_length[15]',
            'jabatan' => 'required'
        ]);

        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman tambah karyawan
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama_lengkap'),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('telepon'),
            'jabatan' => $this->request->getPost('jabatan')
        ];
        // Simpan data ke database
        $inserted = $model->insert( $data);

        if ($inserted) {
            return redirect()->to('/karyawan')->with('success', 'Karyawan baru ditambahkan!');
        } else {
            return redirect()->to('/karyawan')->with('error', 'Gagal menambahkan karyawan!');
        }
    }

    // Menampilkan halaman edit karyawan
    public function editKaryawan($id)
    {
        $model = new UserModel();
        $karyawan = $model->find($id);
        $data = [
            'title' => 'Edit ' . $karyawan['nama'],
            'current_page' => 'karyawan',
            'karyawan' => $karyawan,
            'isAdmin' => false
        ];

        // Kirim data ke view
        return view('admin/editKaryawan', $data);
    }

    // Memproses data update karyawan
    public function updateKaryawan($id)
    {
        $model = new UserModel();

        $validation = $this->validate([
            'nama_lengkap' => 'required|min_length[3]|max_length[50]',
            'nama_pengguna' => 'required|min_length[3]|max_length[50]',
            'email' => 'valid_email',
            'telepon' => 'numeric|min_length[10]|max_length[15]',
            'jabatan' => 'required'
        ]);
        
        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman edit
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama_lengkap'),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('telepon'),
            'jabatan' => $this->request->getPost('jabatan')
        ];
        
        $updated = $model->update($id, $data);

        if ($updated) {
            return redirect()->to('/karyawan')->with('success', 'Data karyawan berhasil diperbarui!');
        } else {
            return redirect()->to('/karyawan')->with('error', 'Data karyawan gagal diperbarui!');
        }
    }

    public function statusKaryawan($id) {
        $model = new UserModel();
        $username = $model->select('nama')->find($id);
        $nonactive = false;
        $active = false;

        $action = $this->request->getPost('action');
        if ($action == 'active') {
            $nonactive = $model->update($id, ['status' => 'aktif']);
            $status = 'diaktifkan!';
        } elseif ($action == 'nonactive') {
            $active = $model->update($id, ['status' => 'nonaktif']);
            $status = 'dinonaktifkan!';
        } else {
            return redirect()->back()->with('error', 'Aksi tidak dikenali. Coba refresh halaman.');
        }
        

        if ($nonactive || $active) {
            return redirect()->to('/karyawan')->with('success', 'Karyawan ' . $username['nama'] . ' berhasil ' . $status);
        } else {
            return redirect()->to('/karyawan')->with('error', 'Karyawan ' . $username['nama'] . ' gagal ' . $status);
        }
    }
    
    public function deleteKaryawan($id) {
        $model = new UserModel();

        $deleted = $model->delete($id);

        if ($deleted) {
            return redirect()->to('/karyawan')->with('success', 'Karyawan berhasil dihapus!');
        } else {
            return redirect()->to('/karyawan')->with('error', 'Karyawan gagal dihapus!');
        }
    }

    // Method untuk Menu Admin
    public function admin()
    {
        $model = new AdminModel();
        $admins = $model->findAll();
        $data = [
            'title' => 'Admin Absensi PMI',
            'current_page' => 'admin',
            'admins' => $admins,
        ];
        return view('admin/admin', $data);
    }
    
    public function addAdmin()
    {
        $data = [
            'title' => 'Add Admin',
            'current_page' => 'admin',
        ];
        return view('admin/addAdmin', $data);
    }

    // Memproses data form tambah admin
    public function saveAdmin()
    {
        $model = new AdminModel();
        // Validasi data form
        $validation = $this->validate([
            'nama_lengkap' => 'required|min_length[3]|max_length[50]',
            'nama_pengguna' => 'required|min_length[3]|max_length[50]',
            'email' => 'valid_email',
            'telepon' => 'numeric|min_length[10]|max_length[15]',
        ]);

        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman tambah admin
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama_lengkap'),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('telepon'),
        ];
        // Simpan data ke database
        $inserted = $model->insert( $data);

        if ($inserted) {
            return redirect()->to('/admin')->with('success', 'Admin baru ditambahkan!');
        } else {
            return redirect()->to('/admin')->with('error', 'Gagal menambahkan admin baru!');
        }
    }

    // Menampilkan halaman edit admin
    public function editAdmin($id)
    {
        $model = new AdminModel();
        $admin = $model->find($id);
        $data = [
            'title' => 'Edit ' . $admin['nama'],
            'current_page' => 'karyawan',
            'admin' => $admin,
            'isAdmin' => true
        ];

        // Kirim data ke view
        return view('admin/editAdmin', $data);
    }

    // Memproses data update admin
    public function updateAdmin($id)
    {
        $model = new AdminModel();

        $validation = $this->validate([
            'nama_lengkap' => 'required|min_length[3]|max_length[50]',
            'nama_pengguna' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email',
            'telepon' => 'required|numeric|min_length[10]|max_length[15]',
        ]);
        
        if (!$validation) {
            // Jika validasi gagal, kembali ke halaman edit
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama_lengkap'),
            'nama_pengguna' => $this->request->getPost('nama_pengguna'),
            'email' => $this->request->getPost('email'),
            'no_telepon' => $this->request->getPost('telepon'),
        ];
        
        $updated = $model->update($id, $data);

        if ($updated) {
            return redirect()->to('/admin')->with('success', 'Data admin berhasil diperbarui!');
        } else {
            return redirect()->to('/admin')->with('error', 'Data admin gagal diperbarui!');
        }
    }

    public function statusAdmin($id) {
        $model = new AdminModel();
        $username = $model->select('nama')->find($id);
        $nonactive = false;
        $active = false;

        $action = $this->request->getPost('action');
        if ($action == 'active') {
            $nonactive = $model->update($id, ['status' => 'aktif']);
            $status = 'diaktifkan!';
        } elseif ($action == 'nonactive') {
            $active = $model->update($id, ['status' => 'nonaktif']);
            $status = 'dinonaktifkan!';
        } else {
            return redirect()->back()->with('error', 'Aksi tidak dikenali. Coba refresh halaman.');
        }
        

        if ($nonactive || $active) {
            return redirect()->to('/admin')->with('success', 'Admin ' . $username['nama'] . ' berhasil ' . $status);
        } else {
            return redirect()->to('/admin')->with('error', 'Admin ' . $username['nama'] . ' gagal ' . $status);
        }
    }
    
    public function deleteAdmin($id) {
        $model = new AdminModel();

        $deleted = $model->delete($id);

        if ($deleted) {
            return redirect()->to('/admin')->with('success', 'Admin berhasil dihapus!');
        } else {
            return redirect()->to('/admin')->with('error', 'Admin gagal dihapus!');
        }
    }

    // Method untuk Menu Laporan
    public function laporan()
    {
        $modelAbsensi = new AbsensiModel();

        // Variabel min dan max waktu untuk filter
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
        // Ambil bulan yang dikirimkan (dari parameter request atau default ke bulan sebelumnya)
        $bulan = $this->request->getVar('bulan') ?? date('m-Y', strtotime('-1 day'));
        
        $bulanan = $modelAbsensi
            ->select('u.id AS user_id, u.nama, absensi.tipe, absensi.tanggal')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('DATE_FORMAT(absensi.tanggal, "%m-%Y")', $bulan) 
            ->findAll();

        $result = [];
        foreach ($bulanan as $row) {
            $userId = $row['user_id'];
            $tanggal = (int)date('d', strtotime($row['tanggal']));

            if (!isset($result[$userId])) {
                $result[$userId] = [
                    'nama' => $row['nama'],
                    'absensi' => array_fill(1, 30, ''),
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
}
