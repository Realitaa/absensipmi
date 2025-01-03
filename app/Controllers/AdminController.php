<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\SakitModel;
use App\Models\CutiModel;
use App\Models\AdminModel;
use App\Models\QRCodeModel;
use CodeIgniter\HTTP\ResponseInterface;

class AdminController extends BaseController
{
    // Method untuk Menu Dashboard
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Admin',
            'current_page' => 'dashboard',
        ];
        return view('admin/dashboard', $data);
    }

    public function getDashboardData()
    {
        $modelUser = new UserModel();
        $modelAbsensi = new AbsensiModel();
        $today = date('Y-m-d');
        $data = [
            'hadir' => $modelAbsensi->where('tipe', 'Hadir')->where('tanggal', $today)->countAllResults(),
            'sakit' => $modelAbsensi->where('tipe', 'Sakit')->where('tanggal', $today)->countAllResults(),
            'cuti' => $modelAbsensi->where('tipe', 'Cuti')->where('tanggal', $today)->countAllResults(),
            'tanpaKeterangan' => $modelUser->where('NOT EXISTS (
                SELECT 1 FROM absensi
                WHERE users.id = absensi.user_id 
                AND absensi.tanggal = CURDATE())')
            ->where('users.status = "aktif"')->countAllResults(),
        ];

        return $this->response->setJSON($data);
    }

    public function getTableData($status)
    {
        $modelUser = new UserModel();
        $modelAbsensi = new AbsensiModel();
        $today = date('Y-m-d');

        switch ($status) {
            case 'hadir':
                $data = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, TIME(absensi.created_at) AS waktu')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Hadir')
            ->where('tanggal', $today)
            ->findAll();
                break;
            case 'sakit':
                $data = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, s.judul, s.deskripsi, s.status, absensi.created_at AS waktu')
            ->join('sakit s', 's.absensi_id = absensi.id')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Sakit')
            ->where('tanggal', $today)
            ->findAll();
                break;
            case 'cuti':
                $data = $modelAbsensi->select('u.id AS kID, u.nama, u.jabatan, c.judul, c.deskripsi, c.status, absensi.created_at AS waktu')
            ->join('cuti c', 'absensi.id = c.absensi_id')
            ->join('users u', 'absensi.user_id = u.id')
            ->where('tipe', 'Cuti')
            ->where('tanggal', $today)
            ->findAll();
                break;
            case 'tanpaKeterangan':
                $data = $modelUser
            ->select('users.id AS kID, users.nama, users.jabatan, users.status, users.email, users.no_telepon')
            ->where('NOT EXISTS (
            SELECT 1 FROM absensi
            WHERE users.id = absensi.user_id 
            AND absensi.tanggal = CURDATE())')
            ->where('users.status = "aktif"')
            ->findAll();
                break;
            default:
                $data = [];
                break;
        }

        return $this->response->setJSON(['status' => $status, 'data' => $data]);
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
            return redirect()->to('/absensipmi/administrator//karyawan')->with('success', 'Karyawan baru ditambahkan!');
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('error', 'Gagal menambahkan karyawan!');
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
            return redirect()->to('/absensipmi/administrator/karyawan')->with('success', 'Data karyawan berhasil diperbarui!');
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('error', 'Data karyawan gagal diperbarui!');
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
            return redirect()->to('/absensipmi/administrator/karyawan')->with('success', 'Karyawan ' . $username['nama'] . ' berhasil ' . $status);
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('error', 'Karyawan ' . $username['nama'] . ' gagal ' . $status);
        }
    }

    public function resetAvatar()
    {
        $model = new UserModel();
        $id = $this->request->getPost('id');

        // Ambil data pengguna berdasarkan ID
        $user = $model->find($id);

        if ($user && !empty($user['foto'])) {
            $filePath = FCPATH . 'userProfile/' . $user['foto']; // Path lengkap ke file

            // Periksa apakah file ada, lalu hapus
            if (is_file($filePath)) {
                unlink($filePath); // Hapus file dari server
            }
        }

        // Reset kolom foto di database
        $restart = $model->update($id, ['foto' => '']);

        if ($restart) {
            return redirect()->to('/absensipmi/administrator/karyawan/edit/' . $id)->with('success', 'Avatar sudah di reset.');
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan/edit/' . $id)->with('error', 'Avatar gagal di reset.');
        }
    }
    
    public function deleteKaryawan($id) {
        $model = new UserModel();

        $deleted = $model->delete($id);

        if ($deleted) {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('success', 'Karyawan berhasil dihapus!');
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('error', 'Karyawan gagal dihapus!');
        }
    }

    public function resetPassword($id) {
        $model = new UserModel();

        $reset = $model->update($id, ['password' => '']);

        if ($reset) {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('success', 'Password berhasil di reset!');
        } else {
            return redirect()->to('/absensipmi/administrator/karyawan')->with('error', 'Gagal reset password!');
        }
    }

    public function deleteAbsensi() {
        $model = new AbsensiModel();
        $id = $this->request->getPost('id');

        $deleted = $model->delete($id);

        if ($deleted) {
            return redirect()->back()->with('success', 'Berhasil menghapus absensi karyawan.');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus absensi karyawan.');
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
            'password' => 'required|min_length[4]',
            'confirm_password' => 'required|matches[password]',
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
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Enkripsi password
        ];
        // Simpan data ke database
        $inserted = $model->insert( $data);

        if ($inserted) {
            return redirect()->to('/absensipmi/administrator/admin')->with('success', 'Admin baru ditambahkan!');
        } else {
            return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Gagal menambahkan admin baru!');
        }
    }

    // Menampilkan halaman edit admin
    public function editAdmin($id)
    {
        $model = new AdminModel();
        $admin = $model->find($id);
        $data = [
            'title' => 'Edit ' . $admin['nama'],
            'current_page' => 'admin',
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
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT), // Enkripsi password
        ];
        
        $updated = $model->update($id, $data);

        if ($updated) {
            return redirect()->to('/absensipmi/administrator/admin')->with('success', 'Data admin berhasil diperbarui!');
        } else {
            return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Data admin gagal diperbarui!');
        }
    }

    public function statusAdmin($id) {
        $model = new AdminModel();
        $adminAktif = $model->where('status', 'aktif')->countAll();
        $username = $model->select('nama')->find($id);
        $nonactive = false;
        $active = false;

        $action = $this->request->getPost('action');
        if ($action == 'active') {
            $nonactive = $model->update($id, ['status' => 'aktif']);
            $status = 'diaktifkan!';
        } elseif ($action == 'nonactive') {
            if ($adminAktif > 1) {
                $active = $model->update($id, ['status' => 'nonaktif']);
                $status = 'dinonaktifkan!';
            } else {
                return redirect()->back()->with('error', 'Minimal ada satu admin yang aktif.');
            }
        } else {
            return redirect()->back()->with('error', 'Aksi tidak dikenali. Coba refresh halaman.');
        }
        

        if ($nonactive || $active) {
            return redirect()->to('/absensipmi/administrator/admin')->with('success', 'Admin ' . $username['nama'] . ' berhasil ' . $status);
        } else {
            return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Admin ' . $username['nama'] . ' gagal ' . $status);
        }
    }
    
    public function deleteAdmin($id) {
        $model = new AdminModel();
        $admin = $model->countAll();
        if ($admin > 1) {
            $deleted = $model->delete($id);
            if ($deleted) {
                return redirect()->to('/absensipmi/administrator/admin')->with('success', 'Admin berhasil dihapus!');
            } else {
                return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Admin gagal dihapus!');
            }
        } else {
            return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Minimal ada satu admin yang aktif');
        }
    }
    
    public function resetAdmin($id) {
        $model = new AdminModel();

        $reset = $model->update($id, ['password' =>  password_hash('udd123', PASSWORD_BCRYPT)]);

        if ($reset) {
            return redirect()->to('/absensipmi/administrator/admin')->with('success', 'Password berhasil di reset!');
        } else {
            return redirect()->to('/absensipmi/administrator/admin')->with('error', 'Gagal reset password!');
        }
    }

    // Method untuk Menu Ketidakhadiran
    public function ketidakhadiran() {
        $modelSakit = new SakitModel();
        $modelCuti = new CutiModel();

        // Data sakit
        $dataSakit = $modelSakit
            ->select('u.nama, u.jabatan, u.foto, a.id AS absensiID, "Sakit" AS tipe, sakit.judul, sakit.deskripsi, sakit.status')
            ->join('absensi a', 'sakit.absensi_id = a.id')
            ->join('users u', 'a.user_id = u.id')
            ->where('sakit.status', 'Menunggu')
            ->findAll();

        // Data cuti
        $dataCuti = $modelCuti
            ->select('u.nama, u.jabatan, u.foto, a.id AS absensiID, "Cuti" AS tipe, cuti.judul, cuti.deskripsi, cuti.status')
            ->join('absensi a', 'cuti.absensi_id = a.id')
            ->join('users u', 'a.user_id = u.id')
            ->where('cuti.status', 'Menunggu')
            ->findAll();

        // Gabungkan data dan hilangkan duplikasi berdasarkan nama
        $dataGabungan = array_merge($dataSakit, $dataCuti);
        $dataUnik = [];
        foreach ($dataGabungan as $item) {
            $dataUnik[$item['nama']] = $item; // Gunakan nama sebagai kunci untuk menghilangkan duplikasi
        }

        // Konversi kembali ke array numerik
        $ketidakhadiran = array_values($dataUnik);

        $data = [
            'title' => 'Pengajuan Ketidakhadiran',
            'current_page' => 'ketidakhadiran',
            'ketidakhadiran' => $ketidakhadiran,
        ];
        return view('admin/ketidakhadiran', $data);
    }

    public function acceptAbsensi() {
        $modelSakit = new SakitModel();
        $modelCuti = new CutiModel();
        $waktu = $this->request->getPost('waktu');
        $tipe = $this->request->getPost('tipe');
        $terima = false;
    
        if ($tipe == 'Cuti') {
            $terima = $modelCuti->where('DATE(created_at)', $waktu)->set(['status' => 'Terima'])->update();
        } elseif ($tipe == 'Sakit') {
            $terima = $modelSakit->where('DATE(created_at)', $waktu)->set(['status' => 'Terima'])->update();
        }
    
        if ($terima) {
            return redirect()->to('/absensipmi/administrator/ketidakhadiran')->with('success', 'Formulir Ketidakhadiran sudah diterima');
        } else {
            return redirect()->to('/absensipmi/administrator/ketidakhadiran')->with('error', 'Gagal menerima formulir ketidakhadiran');
        }
    }
    
    public function rejectAbsensi() {
        $modelSakit = new SakitModel();
        $modelCuti = new CutiModel();
        $waktu = $this->request->getPost('waktu');
        $tipe = $this->request->getPost('tipe');
        $tolak = false;
    
        if ($tipe == 'Cuti') {
            $tolak = $modelCuti->where('DATE(created_at)', $waktu)->delete();
        } elseif ($tipe == 'Sakit') {
            $tolak = $modelSakit->where('DATE(created_at)', $waktu)->delete();
        }

    
        if ($tolak) {
            return redirect()->to('/absensipmi/administrator/ketidakhadiran')->with('success', 'Formulir ketidakhadiran sudah ditolak');
        } else {
            return redirect()->to('/absensipmi/administrator/ketidakhadiran')->with('error', 'Gagal menolak formulir ketidakhadiran');
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
    
    

    // Method untuk Menu Admin
    public function profile($id) {
        $model = new AdminModel();

        $admin = $model->select('nama, nama_pengguna, email, no_telepon')->find($id);

        $data = [
            'title' => 'Profile ' . $admin['nama'],
            'admin' => $admin,
        ];

        return view('admin/profile', $data);
    }

    public function absensi() {
        $model = new QRCodeModel();

        // Reset auto-increment pada tabel tertentu (misalnya 'absensi')
        $db = \Config\Database::connect();
        $query = "ALTER TABLE qrcode AUTO_INCREMENT = 1"; 
        $db->query($query);  // Jalankan query

        $data = [
            'title' => 'Barcode Absensi',
        ];
        return view ('admin/barcode', $data);
    }

    public function generateBarcodeAPI()
    {
        $qrCodeModel = new QRCodeModel();

        // Hitung timestamp batas waktu (lebih dari 10 detik dari sekarang)
        $timeThreshold = date('Y-m-d H:i:s', strtotime('-10 seconds'));
        $id = session('user_data')['UserID'];

        // Hapus kode lama berdasarkan `created_at` dan `user_id`
        $qrCodeModel->where('created_at <', $timeThreshold)
                    ->where('issuer', $id)
                    ->delete();
                    
        // Tambahkan dan kirim kode baru
        $token = bin2hex(random_bytes(4));
        $data = [
            'uniqueCode' => $token,
            'issuer' => $id
        ];
        $newCode = $qrCodeModel->insert($data);

        if ($newCode) {
            $qrCodeURL = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($token);
            // Kembalikan token dan URL dalam format JSON
            return $this->response->setJSON([
                'token' => $token,
                'qrCodeURL' => $qrCodeURL
            ]);
        }
    }

    public function getLogs() 
{
    $modelUser = new UserModel();
    $today = date('Y-m-d');

    $logs = $modelUser->select('users.nama, TIME(a.created_at) as waktu')
        ->join('absensi a', 'users.id = a.user_id')
        ->where('a.tipe', 'Hadir')
        ->where('a.tanggal', $today)
        ->orderBy('a.created_at', 'DESC')
        ->limit(3)
        ->findAll();

    if (!$logs) {
        return $this->response->setJSON(['error' => 'No logs found']);
    }

    return $this->response->setJSON(['logs' => $logs]);
}

    public function ketidakhadiranNotif() {
        $modelCuti = new CutiModel();
        $modelSakit = new SakitModel();

        $menungguCuti = $modelCuti->where('status', 'Menunggu')->countAll();
        $menungguSakit = $modelSakit->where('status', 'Menunggu')->countAll();

        return $this->response->setJSON(['notif' => $menungguCuti + $menungguSakit]);
}
}