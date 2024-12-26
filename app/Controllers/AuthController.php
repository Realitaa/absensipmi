<?php

namespace App\Controllers;
use App\Models\UserModel;

class AuthController extends BaseController
{
    // Controller untuk karyawan
    public function login() {
        $session = session();

        if (!$session->getTempdata('user_data')) {
            // Jika data sementara sudah kedaluwarsa, redirect ke halaman login
            return redirect()->to('/');
        }

        $model = new UserModel();

        $username = $this->request->getVar('username'); 
        $password = $this->request->getVar('password');
        $user = $model->where('Username', $username)->first();

        if ($user && password_verify($password, $user['Password'])) {
            // Simpan informasi ke session
            $session->setTempdata('user_data', [
                'UserID' => $user['id'],
                'Username' => $user['username'],
                'logged_in' => true,
            ], 60 * 60 * 24 * 30); // 30 hari dalam detik

            return redirect()->to('/home');
        } else {
            // Kembali ke login jika password salah
            $session->setFlashdata('error', 'Password salah.');
            return redirect()->to('/')->withInput();
        }
    }
}
