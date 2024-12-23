<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="container py-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Admin</h4>
        </div>
        <div class="card-body">
            <p class="text-danger">Admin adalah akun yang memiliki akses ke halaman admin dan kontrol web absensi. Perhatikan siapa yang menjadi admin!</p>
            <form action="<?= base_url('/admin/save') ?>" method="post">
                    <div class="form-group">
                        <label for="nama_lengkap" class="text-primary">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap (required)" value="<?= old('nama_lengkap') ?>" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="nama_pengguna" class="text-primary">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Masukkan nama pengguna (required)" value="<?= old('nama_pengguna') ?>" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="text-primary">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email pengguna (required)" value="<?= old('email') ?>" required>
                    </div>

                    <div class="form-group mt-3">
                        <label for="telepon" class="text-primary">Nomor Telepon</label>
                        <input type="tel" name="telepon" id="telepon" class="form-control" placeholder="Masukkan nomor telepon (required)" value="<?= old('telepon') ?>" required>
                    </div>

                    <?php if (session()->get('errors')) : ?>
                        <div class="alert alert-danger my-2">
                            <ul>
                                <?php foreach (session()->get('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Tambah Karyawan
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>

<!-- <div class="form-group mt-3">
    <label for="jabatan" class="text-primary">Jabatan</label>
    <select name="jabatan" id="jabatan" class="form-control" required>
        <option value="" disabled selected>Pilih jabatan</option>
        <option value="Manager">Manager</option>
        <option value="Supervisor">Supervisor</option>
        <option value="Staff">Staff</option>
        <option value="Intern">Intern</option>
    </select>
</div> -->