<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="container py-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Admin</h4>
        </div>
        <div class="card-body">
            <p class="text-danger">Admin adalah akun yang memiliki akses ke halaman admin dan kontrol web absensi. Perhatikan siapa yang menjadi admin!</p>
            <form action="<?= base_url('/administrator/admin/save') ?>" method="post">
                    <div class="form-group">
                        <label for="nama_lengkap" class="text-primary">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan nama lengkap (required)" value="<?= old('nama_lengkap') ?>" required>
                        <?php if (session('errors.nama_lengkap')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_lengkap') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="nama_pengguna" class="text-primary">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control <?= session('errors.nama_pengguna') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan nama pengguna (required)" value="<?= old('nama_pengguna') ?>" required>
                        <?php if (session('errors.nama_pengguna')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_pengguna') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="text-primary">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan email pengguna (required)" value="<?= old('email') ?>" required>
                        <?php if (session('errors.email')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="telepon" class="text-primary">Nomor Telepon</label>
                        <input type="tel" name="telepon" id="telepon" class="form-control <?= session('errors.telepon') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan nomor telepon (required)" value="<?= old('telepon') ?>" required>
                        <?php if (session('errors.telepon')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.telepon') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password" class="text-primary">Password</label>
                        <input type="password" name="password" id="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan password min. 8 karakter (required)" required>
                        <?php if (session('errors.password')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="confirm_password" class="text-primary">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan ulang password (required)" required>
                        <?php if (session('errors.confirm_password')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.confirm_password') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Tambah Admin
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>