<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AdminModel;

class AuthController extends BaseController
{
    // Controller untuk karyawan
    public function login()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('remember_me');

        $user = $model->where('nama_pengguna', $username)->first();
        if ($user) {
            if ($user['status'] == 'aktif') {
                // Jika password kosong yang diinput dan di database kosong
                if (empty($password) && empty($user['Password'])) {
                    $session->setTempdata('user_data', [
                        'UserID' => $user['id'],
                        'Username' => $user['nama_pengguna'],
                        'Role' => 'karyawan',
                        'Foto' => $user['foto'],
                        'logged_in' => true,
                        'isSecureAccount' => false, // Akun tidak aman karena password kosong
                    ]);
                    return redirect()->to('/home');
                }

                //Verifikasi password jika ada
                if (password_verify($password, $user['password'])) {
                    // Periksa apakah "Remember Me" dicentang
                    $sessionDuration = $rememberMe ? (60 * 60 * 24 * 30) : 0; // 30 hari jika "Remember Me" dicentang, sesi berakhir saat browser ditutup jika tidak.
    
                    // Simpan informasi ke session dengan durasi tertentu
                    $session->setTempdata('user_data', [
                        'UserID' => $user['id'],
                        'Username' => $user['nama_pengguna'],
                        'Role' => 'karyawan',
                        'Foto' => $user['foto'],
                        'logged_in' => true,
                        'isSecureAccount' => true, //Akun aman
                    ], $sessionDuration);
    
                    return redirect()->to('/home');
                } else {
                    // Kembali ke login jika password salah
                    $session->setFlashdata('error', 'Password salah.');
                    return redirect()->to('/')->withInput();
                }
            } else {
                // Kembali ke login jika status nonaktif
                $session->setFlashdata('error', 'Akun kamu dinonaktifkan.');
                return redirect()->to('/')->withInput();
            }
        } else {
            // Kembali ke login jika user tidak ditemukan
            $session->setFlashdata('error', 'Akun kamu tidak ditemukan.');
            return redirect()->to('/');
        }
        
    }

    // Controller untuk Admin
    public function AdminValidation()
    {
        $session = session();
        $model = new AdminModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $user = $model->where('Username', $username)->first();

        if ($user && $user['status'] == 'aktif') {
            if ($user && password_verify($password, $user['Password'])) {
                // Simpan informasi ke session
                $session->setTempdata('user_data', [
                    'UserID' => $user['id'],
                    'Username' => $user['username'],
                    'Role' => 'admin',
                    'logged_in' => true,
                ]);

                return redirect()->to('/administrator/dashboard');
            } else {
                // Kembali ke login jika password salah
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/administrator')->withInput();
            }
        } else {
            // Kembali ke login jika status nonaktif
            $session->setFlashdata('error', 'Akun kamu di nonaktifkan.');
            return redirect()->to('/administrator')->withInput();
        }
    }

    public function logout($role)
    {
        $session = session();
        $session->destroy();  // Menghancurkan semua data sesi

        // Menentukan redirect berdasarkan role
        if ($role == 'user') {
            return redirect()->to('/');
        } else if ($role == 'admin') {
            return redirect()->to('/administrator');
        }

        // Jika role tidak sesuai, redirect ke halaman login
        return redirect()->to('/');
    }
}
