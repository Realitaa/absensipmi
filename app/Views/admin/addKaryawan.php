<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="container py-3">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Karyawan</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('/karyawan/save') ?>" method="post">
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
                        <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan nama pengguna (required)" value="<?= old('nama_pengguna') ?>" required>
                        <?php if (session('errors.nama_pengguna')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.nama_pengguna') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="email" class="text-primary">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan email pengguna" value="<?= old('email') ?>">
                        <?php if (session('errors.email')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="telepon" class="text-primary">Nomor Telepon</label>
                        <input type="tel" name="telepon" id="email" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan nomor telepon" value="<?= old('telepon') ?>">
                        <?php if (session('errors.email')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.email') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mt-3">
                        <label for="jabatan" class="text-primary">Jabatan</label>
                        <input type="jabatan" name="jabatan" id="jabatan" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                        placeholder="Masukkan jabatan (required)" value="<?= old('jabatan') ?>" required>
                        <?php if (session('errors.jabatan')) : ?>
                            <div class="invalid-feedback">
                                <?= session('errors.jabatan') ?>
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