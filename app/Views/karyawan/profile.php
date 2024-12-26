<?= $this->extend('template/navbarKaryawan'); ?>
<?= $this->section('content'); ?>

<div class="container py-3">
    <div class="row text-center pb-3">
        <div class="col-md-5">
            <img src="https://via.assets.so/img.jpg?w=300&h=300&tc=blue&bg=#cecece" alt="">
        </div>
        <div class="col-md-7 align-content-center">
            <h2 class="pt-2"><?= esc($karyawan['nama']); ?></h2>
            <h4><?= esc($karyawan['jabatan']); ?></h4>
            <p><i class="bi bi-envelope"></i> <?= esc($karyawan['email']); ?></p>
            <p><i class="bi bi-phone"></i> <?= esc($karyawan['no_telepon']); ?></p>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit Profile</button>
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
                            <td><?= $absen['tipe'] == 'hadir' ? '<a href="/absensi/' . esc($absen["ID"]) . '" class="btn btn-primary">Rincian</a> ' : esc($absen['waktu']) ?? '-' ; ?> <?= $authority == 'admin' ? '<button class="btn btn-danger">Batalkan</button>' : '' ; ?></td>
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

<!-- Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Mengubah Profil Saya</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('/edit/myself'); ?>" method="post">
            <div class="form-group">
                <label for="email" class="text-primary">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan email" value="<?= esc($karyawan['email']); ?>">
            </div>
            <div class="form-group mt-3">
                <label for="telepon" class="text-primary">Nomor Telepon (WhatsApp)</label>
                <input type="tel" name="telepon" id="telepon" class="form-control" placeholder="Masukkan nomor telepon (WhatsApp)" value="<?= esc($karyawan['no_telepon']); ?>">
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

<?= $this->endSection('content'); ?>