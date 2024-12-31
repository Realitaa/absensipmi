<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\SakitModel;
use App\Models\CutiModel;
use App\Models\QRCodeModel;

class KaryawanController extends BaseController
{
    public function index()
    {
        $model = new AbsensiModel();
        $id = session('user_data')['UserID'];
        $today = date('Y-m-d');
        
        $absensi = $model
            ->select('absensi.tanggal, absensi.tipe, absensi.created_at, c.judul AS judulCuti, c.deskripsi AS deskripsiCuti, c.status AS statusCuti, s.judul AS judulSakit, s.deskripsi AS deskripsiSakit, s.status AS statusSakit')
            ->join('cuti c', 'c.absensi_id = absensi.id', 'left')
            ->join('sakit s', 's.absensi_id = absensi.id', 'left')
            ->where('tanggal', $today)
            ->where('user_id', $id)
            ->first();
        $data = [
            'title' => 'Absensi Karyawan',
            'absensi' => $absensi
        ];
        return view('karyawan/home', $data);
    }
    
    public function kehadiran()
    {
        return view('karyawan/kehadiran', ['title' => 'Barcode Absensi']);
    }

    public function ketidakhadiran()
    {
        $modelAbsensi = new AbsensiModel();
        $modelSakit = new SakitModel();
        $modelCuti = new CutiModel();
    
        // Validasi input form
        $validation = $this->validate([
            'judul' => 'required|max_length[50]',
            'waktu' => 'required', 
            'deskripsi' => 'required',
            'tipe' => 'required',
            'lampiran.*' => 'permit_empty|is_image[lampiran.*]|max_size[lampiran.*,2048]|mime_in[lampiran.*,image/jpg,image/jpeg,image/png,application/pdf]',
        ]);
    
        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Ambil data POST
        $tipe = $this->request->getPost('tipe');
        $waktu = $this->request->getPost('waktu');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');
        $user_id = session('user_data')['UserID'];
    
        // Ambil tanggal
        $tanggalRange = explode(' to ', $waktu);
        if (count($tanggalRange) === 1) $tanggalRange[1] = $tanggalRange[0];
        $tanggalMulai = date('Y-m-d', strtotime($tanggalRange[0]));
        $tanggalSelesai = date('Y-m-d', strtotime($tanggalRange[1]));

        // Cek apakah ada tanggal yang sudah terisi
        $existingDates = $modelAbsensi
            ->where('user_id', $user_id)
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalSelesai)
            ->findAll();

        if (!empty($existingDates)) {
            $dates = array_column($existingDates, 'tanggal');
            $dateList = implode(', ', $dates);
            return redirect()->back()->withInput()->with('error', "Anda sudah mengisi absensi untuk hari-hari berikut: $dateList.");
        }
    
        // Proses lampiran (jika ada)
        $uploadedFiles = $this->request->getFileMultiple('lampiran');
        $folderName = null;
    
        if (!empty($uploadedFiles)) {
            $folderName = 'user_' . $user_id . '_' . time(); // Nama folder unik
            $folderPath = FCPATH . 'lampiran/' . $folderName;
    
            if (!is_dir($folderPath)) {
                mkdir($folderPath, 0755, true); // Buat folder jika belum ada
            }
    
            foreach ($uploadedFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $file->move($folderPath);
                }
            }
        }
    
        // Simpan absensi ke database
        $currentDate = strtotime($tanggalMulai);
        $endDate = strtotime($tanggalSelesai);
        while ($currentDate <= $endDate) {
            $tanggalAbsensi = date('Y-m-d', $currentDate);
    
            $dataAbsensi = [
                'user_id' => $user_id,
                'tanggal' => $tanggalAbsensi,
                'tipe' => $tipe,
                'lampiran' => $folderName, // Simpan nama folder
            ];
            $absen = $modelAbsensi->insert($dataAbsensi);
    
            if ($absen) {
                $absensi = $modelAbsensi->where('user_id', $user_id)->where('tanggal', $tanggalAbsensi)->first();
                $details = [
                    'absensi_id' => $absensi['id'],
                    'judul' => $judul,
                    'deskripsi' => $deskripsi,
                ];
    
                if ($tipe === "Sakit") {
                    $modelSakit->insert($details);
                } elseif ($tipe === "Cuti") {
                    $modelCuti->insert($details);
                }
            }
    
            $currentDate = strtotime("+1 day", $currentDate);
        }
    
        return redirect()->to('/home')->with('success', 'Formulir Ketidakhadiran sudah dikirim dan menunggu dikonfirmasi admin. Terima kasih.');
    }
    

public function karyawan($id)
{
    $modelUser = new UserModel();
    $modelAbsensi = new AbsensiModel();

    
    if ($id == 'me') {
        $id = session('user_data')['UserID'];
    }
    $karyawan = $modelUser->select('id, nama, nama_pengguna, email, no_telepon, jabatan, foto')->find($id);

    $min_month = $modelAbsensi->select("DATE_FORMAT(MIN(tanggal), '%m-%Y') AS min_date")->where('user_id', $id)->first()['min_date'];
    $max_month = $modelAbsensi->select("DATE_FORMAT(MAX(tanggal), '%m-%Y') AS max_date")->where('user_id', $id)->first()['max_date'];

    if ($karyawan) {
    $absensi = $modelAbsensi->select('absensi.id AS ID, absensi.tanggal, absensi.tipe, TIME(absensi.created_at) AS waktu, c.judul, c.deskripsi, c.status, s.judul, s.deskripsi, s.status')
        ->join('cuti c', 'c.absensi_id = absensi.id', 'left')
        ->join('sakit s', 's.absensi_id = absensi.id', 'left')
        ->where('absensi.user_id', $id)
        ->find();
    } else {
        return redirect()->back()->with('error', 'Karyawan tidak ditemukan.');
    }

    $data = [
        'title' => 'Profil Karyawan',
        'karyawan' => $karyawan,
        'absensi' => $absensi,
        'authority' => session('user_data')['Role'], // Membatasi tindakan user
        'current_page' => 'karyawan', // Dibutuhkan jika view diakses admin
        'min_month' => $min_month,
        'max_month' => $max_month,
    ];
    return view('karyawan/profile', $data);
}

public function updateKaryawan()
{
    $model = new UserModel();
    $id = session('user_data')['UserID'];

    // Validasi input form
    $validation = $this->validate([
        'email' => 'permit_empty|valid_email',
        'telepon' => 'permit_empty|numeric|min_length[10]|max_length[15]',
        'password' => 'permit_empty',
        'confirm_password' => 'permit_empty|matches[password]',
        'avatar' => 'permit_empty|is_image[avatar]|max_size[avatar,1024]|mime_in[avatar,image/jpg,image/jpeg,image/png]'
    ]);

    if (!$validation) {
        $errors = implode(', ', $this->validator->getErrors()); // Gabungkan array menjadi string
        return redirect()->back()->withInput()->with('error', $errors);
    }

    // Ambil data dari form
    $data = [
        'email' => $this->request->getPost('email'),
        'no_telepon' => $this->request->getPost('telepon'),
    ];

    // Hash password jika diisi
    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_BCRYPT);
    }

    // Periksa apakah folder userProfile sudah ada, jika belum buat foldernya
    $userProfileDir = FCPATH . 'userProfile';
    if (!is_dir($userProfileDir)) {
        mkdir($userProfileDir, 0777, true); // Buat folder dengan permission 0777 dan buat subfolder jika diperlukan
    }

    // Proses file upload (jika ada)
    $avatar = $this->request->getFile('avatar');
    if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
        // Ambil informasi karyawan sebelumnya untuk cek foto lama
        $karyawan = $model->find($id);
        $oldPhotoPath = $karyawan['foto']; // Path foto lama dari database

        // Hapus foto lama jika ada
        if (!empty($oldPhotoPath) && file_exists(FCPATH . 'userProfile/' . $oldPhotoPath)) {
            unlink(FCPATH . 'userProfile/' . $oldPhotoPath);
        }

        // Pindahkan file ke folder tujuan
        $newFileName = $avatar->getRandomName();
        $avatar->move($userProfileDir, $newFileName);

        // Simpan path file baru ke database
        $data['foto'] = $newFileName;
    }

    // Update data di database
    $updated = $model->update($id, $data);

    if ($updated) {
        if (!empty($password)) {
            $session = session();
            $session->destroy(); // Menghancurkan semua data sesi
            return redirect()->to('/?success=' . urlencode('Password diperbarui. Silahkan login ulang.'));
        } else {
            return redirect()->to('/karyawan/me')->with('success', 'Profil berhasil diperbarui!');
        }
    } else {
        return redirect()->to('/karyawan/me')->with('error', 'Profil gagal diperbarui!');
    }
}


public function hadir()
{
    $code = $this->request->getPost('code'); // Ambil kode dari AJAX
    $modelQR = new QRCodeModel();
    $modelAbsensi = new AbsensiModel();
    $today = date('Y-m-d');

    // Cari kode yang cocok di database
    $qrCode = $modelQR->where('uniqueCode', $code)
                    ->first();

    if ($qrCode) {
        $data = [
            'user_id' => session('user_data')['UserID'],
            'tanggal' => $today,
            'tipe' => 'Hadir'
            
        ];

        $hadir = $modelAbsensi->insert($data);

        if ($hadir) {
            // Kembalikan respons sukses
            return $this->response->setJSON(['status' => 'success']);
        }
    } else {
        // Kembalikan respons gagal
        return $this->response->setJSON(['status' => 'failed', 'message' => 'Kode QR tidak valid. Silakan coba lagi.']);
    }
}

public function getUserBulananData()
    {
        $modelAbsensi = new AbsensiModel();
        // Ambil bulan yang dikirimkan (dari parameter request atau default ke bulan sebelumnya)
        $bulan = $this->request->getVar('bulan') ?? date('m-Y', strtotime('-1 day'));
        $id = $this->request->getVar('id');
        
        $bulanan = $modelAbsensi
            ->select('absensi.id, tanggal, tipe, TIME(absensi.created_at) AS waktu, c.status AS cStatus, s.status AS sStatus')
            ->join('cuti c', 'c.absensi_id = absensi.id', 'left')
            ->join('sakit s', 's.absensi_id = absensi.id', 'left')
            ->where('DATE_FORMAT(tanggal, "%m-%Y")', $bulan) 
            ->where('user_id', $id)
            ->findAll();

        return $this->response->setJSON($bulanan);
    }

    public function rincianKetidakhadiran($id) {
        $modelUser = new UserModel();
        $modelAbsensi = new AbsensiModel();
        $modelCuti = new CutiModel();
        $modelSakit = new SakitModel();
    
        // Ambil data absensi berdasarkan ID
        $absensi = $modelAbsensi->select('id, user_id, tanggal, tipe, DATE(created_at) AS waktu')->find($id);
        if (!$absensi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Data absensi tidak ditemukan.");
        }

        $karyawan = $modelUser->select('id, nama, jabatan, email, no_telepon')->find($absensi['user_id']);
    
        $durasi = $modelAbsensi->select("tipe, MIN(tanggal) AS mulai, MAX(tanggal) AS selesai")
            ->where('user_id', $absensi['user_id']) // Pastikan hanya untuk user yang sama
            ->where('DATE(created_at)', $absensi['waktu'])
            ->groupBy('id') // Tambahkan klausa GROUP BY
            ->first();

    
        // Ambil data terkait cuti atau sakit berdasarkan tipe absensi
        $detail = null;
        if ($absensi['tipe'] === 'Cuti') {
            $detail = $modelCuti->select('judul, deskripsi, status, lampiran')
                ->where('absensi_id', $id)
                ->first();
        } elseif ($absensi['tipe'] === 'Sakit') {
            $detail = $modelSakit->select('judul, deskripsi, status, lampiran')
                ->where('absensi_id', $id)
                ->first();
        }
    
        $data = [
            'title' => 'Rincian Absensi',
            'authority' => session('user_data')['Role'],
            'current_page' => 'karyawan',
            'absensi' => $absensi,
            'karyawan' => $karyawan,
            'durasi' => $durasi,
            'detail' => $detail,
        ];
    
        return view('karyawan/rincianKetidakhadiran', $data);
    }
    
}
