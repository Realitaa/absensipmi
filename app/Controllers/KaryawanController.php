<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\SakitModel;
use App\Models\CutiModel;

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
    ]);

    if (!$validation) {
        // Jika validasi gagal, kembali ke halaman sebelumnya
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    // Ambil data yang dikirimkan melalui POST
    $tipe = $this->request->getPost('tipe');
    $waktu = $this->request->getPost('waktu');
    $judul = $this->request->getPost('judul');
    $deskripsi = $this->request->getPost('deskripsi');
    
    // Ambil user_id dari session (misalnya, menggunakan session atau autentikasi)
    // $user_id = session()->get('user_id');
    $user_id = 1; // Simulasi user_id 
    
    // Parse tanggal dari range (tanggal pertama dan kedua)
    $tanggalRange = explode(' to ', $waktu);

    // Periksa apakah hanya satu tanggal yang dipilih
    if (count($tanggalRange) === 1) {
        $tanggalRange[1] = $tanggalRange[0]; // Jika hanya satu tanggal, jadikan tanggalMulai dan tanggalSelesai sama
    } elseif (count($tanggalRange) !== 2) {
        // Jika format tanggal tidak valid
        return redirect()->back()->with('errors', ['Waktu ketidakhadiran tidak valid.']);
    }

    $tanggalMulai = date('Y-m-d', strtotime($tanggalRange[0])); // Tanggal mulai
    $tanggalSelesai = date('Y-m-d', strtotime($tanggalRange[1])); // Tanggal selesai

    // Proses absensi untuk setiap tanggal dalam rentang waktu
    $currentDate = strtotime($tanggalMulai);
    $endDate = strtotime($tanggalSelesai);
    
    while ($currentDate <= $endDate) {
        // Format tanggal untuk setiap iterasi
        $tanggalAbsensi = date('Y-m-d', $currentDate);

        // Simpan data absensi
        $dataAbsensi = [
            'user_id' => $user_id,
            'tanggal' => $tanggalAbsensi,
            'tipe' => $tipe,
        ];
        $absen = $modelAbsensi->insert($dataAbsensi);

        if ($absen) {
            // Ambil id absensi yang baru disimpan
            $absensi = $modelAbsensi->where('user_id', $user_id)->where('tanggal', $tanggalAbsensi)->first();

            $sakit = false;
            $cuti = false;
            // Simpan data Sakit atau Cuti sesuai tipe
            if ($tipe == "Sakit") {
                $dataSakit = [
                    'absensi_id' => $absensi['id'],
                    'judul' => $judul,
                    'deskripsi' => $deskripsi,
                ];
                $sakit = $modelSakit->insert($dataSakit);
            } elseif ($tipe == "Cuti") {
                $dataCuti = [
                    'absensi_id' => $absensi['id'],
                    'judul' => $judul,
                    'deskripsi' => $deskripsi,
                ];
                $cuti = $modelCuti->insert($dataCuti);
            }
        }

        // Lanjutkan ke tanggal berikutnya
        $currentDate = strtotime("+1 day", $currentDate);
    }

    // Redirect ke halaman sukses jika Sakit atau Cuti berhasil disimpan
    if ($sakit || $cuti) {
        return redirect()->to('/home')->with('success', 'Formulir Ketidakhadiran sudah dikirim. Terima kasih.');
    }
}

public function karyawan($id)
{
    $modelUser = new UserModel();
    $modelAbsensi = new AbsensiModel();
    if ($id = 'me') {
        $id = session('user_data')['UserID'];
    }
    $karyawan = $modelUser->select('id, nama, nama_pengguna, email, no_telepon, jabatan, foto')->find($id);

    if ($karyawan) {
    $absensi = $modelAbsensi->select('absensi.id AS ID, absensi.tanggal, absensi.tipe, absensi.tanggal, absensi.created_at AS waktu, c.judul, c.deskripsi, s.judul, s.deskripsi')
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
        'authority' => '', // Membatasi tindakan user
    ];
    return view('karyawan/profile', $data);
}

public function updateKaryawan()
{
    $model = new UserModel();

    $id = session('user_data')['UserID'];

    $validation = $this->validate([
        'email' => 'valid_email',
        'telepon' => 'numeric|min_length[10]|max_length[15]',
        'confirm_password' => 'matches[password]',
    ]);
    
    if (!$validation) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors())->with('error', 'Data tidak valid!');
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

    // Update data di database
    $updated = $model->update($id, $data);

    if ($updated) {
        if (!empty($password)) {
            return redirect()->to('/')->with('success', 'Password di perbarui. Silahkan login ulang.');
        } else {
            return redirect()->to('/karyawan/me')->with('success', 'Profil berhasil diperbarui!');
        }
    } else {
        return redirect()->to('/karyawan//me')->with('error', 'Profil gagal diperbarui!');
    }
}
}
