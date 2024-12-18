<?php

namespace App\Controllers;

class Karyawan extends BaseController
{
    public function index()
    {
        // Cek status absensi (simulasi data untuk sementara)
        $statusAbsensi = false; // Ubah ke true jika sudah absen
        return view('karyawan/index', ['statusAbsensi' => $statusAbsensi]);
    }

    public function izin()
    {
        return view('karyawan/izin');
    }

    public function kirimIzin()
    {
        $validation = $this->validate([
            'alasan' => 'required',
            'deskripsi' => 'required|min_length[5]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
        }

        $data = $this->request->getPost();
        // Simulasi penyimpanan data, nanti gunakan Model
        return redirect()->to('/karyawan')->with('success', 'Izin telah dikirim.');
    }

    public function cuti()
    {
        return view('karyawan/cuti');
    }

    public function kirimCuti()
    {
        $validation = $this->validate([
            'waktu' => 'required',
            'alasan' => 'required|min_length[5]',
            'lampiran' => 'uploaded[lampiran]|max_size[lampiran,2048]|ext_in[lampiran,png,jpg,pdf]',
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid. Silakan periksa kembali.');
        }

        $data = $this->request->getPost();
        // Simulasi penyimpanan data, nanti gunakan Model
        return redirect()->to('/karyawan')->with('success', 'Permohonan cuti telah dikirim.');
    }

    public function riwayat()
    {
        // Data simulasi riwayat absensi
        $data['riwayat'] = [
            ['tanggal' => '2024-12-15', 'jenis' => 'Absensi', 'status' => 'Hadir'],
            ['tanggal' => '2024-12-14', 'jenis' => 'Izin', 'status' => 'Disetujui'],
            ['tanggal' => '2024-12-13', 'jenis' => 'Cuti', 'status' => 'Ditolak'],
        ];
        return view('karyawan/riwayat', $data);
    }

}
