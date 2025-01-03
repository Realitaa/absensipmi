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
                if (empty($password) && empty($user['password'])) {
                    $session->setTempdata('user_data', [
                        'UserID' => $user['id'],
                        'Username' => $user['nama_pengguna'],
                        'Role' => 'karyawan',
                        'Foto' => $user['foto'],
                        'logged_in' => true,
                        'isSecureAccount' => false, // Akun tidak aman karena password kosong
                    ], 7200);
                    return redirect()->to('/absensipmi/home');
                }

                //Verifikasi password jika ada
                if (password_verify($password, $user['password'])) {
                    // Periksa apakah "Remember Me" dicentang
                    $sessionDuration = $rememberMe ? (60 * 60 * 24 * 30) : 7200; // 30 hari jika "Remember Me" dicentang, 2 jam default.
    
                    // Simpan informasi ke session dengan durasi tertentu
                    $session->setTempdata('user_data', [
                        'UserID' => $user['id'],
                        'Username' => $user['nama_pengguna'],
                        'Role' => 'karyawan',
                        'Foto' => $user['foto'],
                        'logged_in' => true,
                        'isSecureAccount' => true, //Akun aman
                    ], $sessionDuration);
    
                    return redirect()->to('/absensipmi/home');
                } else {
                    // Kembali ke login jika password salah
                    $session->setFlashdata('error', 'Password salah.');
                    return redirect()->to('/absensipmi')->withInput();
                }
            } else {
                // Kembali ke login jika status nonaktif
                $session->setFlashdata('error', 'Akun kamu dinonaktifkan.');
                return redirect()->to('/absensipmi')->withInput();
            }
        } else {
            // Kembali ke login jika user tidak ditemukan
            $session->setFlashdata('error', 'Akun kamu tidak ditemukan.');
            return redirect()->to('/absensipmi');
        }
    }

    // Controller untuk Admin
    public function AdminValidation()
    {
        $session = session();
        $model = new AdminModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');
        $user = $model->where('nama_pengguna', $username)->first();

        if ($user) {
            if ($user['status'] == 'aktif') {
                if (password_verify($password, $user['password'])) {
                    // Simpan informasi ke session
                    $session->setTempdata('user_data', [
                        'UserID' => $user['id'],
                        'Username' => $user['nama_pengguna'],
                        'Foto' => $user['foto'],
                        'Role' => 'admin',
                        'logged_in' => true,
                    ], 60 * 60 * 24); // 1 hari
    
                    return redirect()->to('/absensipmi/administrator/dashboard');
                } else {
                    // Kembali ke login jika password salah
                    $session->setFlashdata('error', 'Password salah.');
                    return redirect()->to('/absensipmi/administrator')->withInput();
                }
            } else {
                // Kembali ke login jika akun dinonaktifkan
                $session->setFlashdata('error', 'Akun kamu dinonaktifkan.');
                return redirect()->to('/absensipmi/administrator');
            }
        } else {
            // Kembali ke login jika akun tidak terdaftar
            $session->setFlashdata('error', 'Akun tidak terdaftar.');
            return redirect()->to('/absensipmi/administrator');
        }
    }

    public function logout($role)
    {
        $session = session();
        $session->destroy();  // Menghancurkan semua data sesi

        // Menentukan redirect berdasarkan role
        if ($role == 'user') {
            return redirect()->to('/absensipmi');
        } else if ($role == 'admin') {
            return redirect()->to('/absensipmi/administrator');
        }

        // Jika role tidak sesuai, redirect ke halaman login
        return redirect()->to('/absensipmi');
    }
}
