<?= session('user_data')['Role'] == 'admin' ? $this->extend('template/navbarAdmin') : $this->extend('template/navbarKaryawan'); ?>
<?= $this->section('content'); ?>

<?php 
// Menggunakan service URI untuk mendapatkan segmen URL
$uri = service('uri');

// Ambil segmen ke-2 dari URL
$segment2 = $uri->getSegment(2);
?>

<div class="container py-3">
    <div class="row text-center pb-3">
        <div class="col-md-5">
            <img src="<?= !empty(esc($karyawan['foto'])) ? '/userProfile/' . esc($karyawan['foto']) : '/user.png' ; ?>" alt="User Avatar" width="300px">
        </div>
        <div class="col-md-7 align-content-center">
            <h2 class="pt-2"><?= esc($karyawan['nama']); ?></h2>
            <h4><?= esc($karyawan['jabatan']); ?></h4>
            <p><i class="bi bi-envelope"></i> <?= esc($karyawan['email']); ?></p>
            <p><i class="bi bi-phone"></i> <?= esc($karyawan['no_telepon']); ?></p>
            <?= $segment2 === 'me' ? '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit Profile</button>' : '' ; ?>
        </div>
    </div>
    <div class="container py-3">
        <h3>Tabel Absensi Karyawan</h3>

            <table class="table text-center">
                <thead>
                    <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Absensi</th>
                    <th scope="col">Detail</th>
                    </tr>
                </thead>
                <?php 
                $i = 1;
                if ($absensi) : ?>
                    <?php foreach ($absensi as $absen) : ?>
                        <tr>
                            <th scope="row"><?= $i++; ?></th>
                            <td><?= esc($absen['tanggal']); ?></td>
                            <td><?= esc($absen['tipe']); ?></td> 
                            <td>
                                <?php if ($absen['tipe'] == 'Hadir') : ?>
                                    <?= esc($absen['waktu']) ?? '-' ; ?>
                                <?php elseif ($absen['tipe'] == 'Sakit' || $absen['tipe'] == 'Cuti') : ?>
                                    <a href="/administrator/absensi/<?= esc($absen['ID']); ?>" class="btn btn-primary">Rincian</a>
                                <?php endif; ?>
                                <?= $authority == 'admin' ? '<button class="btn btn-danger">Batalkan</button>' : '' ; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Tidak ada data</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
    </div>
</div>

<?php if ($segment2 === 'me') : ?>
<!-- Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Mengubah Profil Saya</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('/karyawan/update/me'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email" class="text-primary">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan email (wajib)" value="<?= esc($karyawan['email']); ?>">
            </div>
            <div class="form-group mt-3">
                <label for="telepon" class="text-primary">Nomor Telepon (WhatsApp)</label>
                <input type="tel" name="telepon" id="telepon" class="form-control" placeholder="Masukkan nomor telepon (wajib)" value="<?= esc($karyawan['no_telepon']); ?>">
            </div>
            <div class="form-group mt-3">
                <label for="password" class="text-primary">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password (kosongkan jika tidak diubah)">
            </div>
            <div class="form-group mt-3">
                <label for="confirm_password" class="text-primary">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password (kosongkan jika tidak diubah)">
            </div>
            <div class="form-group mt-3">
                <label for="avatar" class="text-primary">Foto Profil (1:1 disarankan. <span class="text-danger">MAX file size: 1 MB</span>) </label>
                <input type="file" name="avatar" id="avatar" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ($authority == 'admin') : ?>
<!-- Modal -->
<div class="modal fade" id="batalAbsenModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Batalkan Absensi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Batalkan absensi karyawan ini?
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Ya</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?= $this->endSection('content'); ?>