<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

    <div class="container py-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4>Edit Data admin</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/update/' . $admin['id']) ?>" method="post">
                    <!-- Nama Pengguna -->
                    <div class="form-group">
                        <label for="nama_lengkap" class="text-warning">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                            value="<?= old('nama_lengkap') ?: $admin['nama'] ?>" placeholder="Masukkan nama lengkap (required)" required>
                            <?php if (session('errors.nama_lengkap')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.nama_lengkap') ?>
                                </div>
                            <?php endif; ?>
                    </div>
    
                    <!-- Nama Pengguna -->
                    <div class="form-group">
                        <label for="nama_pengguna" class="text-warning">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control <?= session('errors.nama_pengguna') ? 'is-invalid' : '' ?>" 
                            value="<?= old('nama_pengguna') ?: $admin['nama_pengguna'] ?>" placeholder="Masukkan nama pengguna (required)" required>
                            <?php if (session('errors.nama_pengguna')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.nama_pengguna') ?>
                                </div>
                            <?php endif; ?>
                    </div>
    
                    <!-- Email -->
                    <div class="form-group mt-3">
                        <label for="email" class="text-warning">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                            value="<?= old('email') ?: $admin['email'] ?>" placeholder="Masukkan email pengguna (required)" required>
                            <?php if (session('errors.email')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.email') ?>
                                </div>
                            <?php endif; ?>
                    </div>
    
                    <!-- Nomor Telepon -->
                    <div class="form-group mt-3">
                        <label for="telepon" class="text-warning">Nomor Telepon</label>
                        <input type="tel" name="telepon" id="telepon" class="form-control <?= session('errors.telepon') ? 'is-invalid' : '' ?>" 
                            value="<?= old('telepon') ?: $admin['no_telepon'] ?>" placeholder="Masukkan nomor telepon (required)" required>
                            <?php if (session('errors.telepon')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.telepon') ?>
                                </div>
                            <?php endif; ?>
                    </div>

                    <!-- Tombol Update -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Data
                        </button>
                        <?php if (esc($admin['status']) === 'aktif'): ?>
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#nonactiveModal">
                                <i class="fas fa-save"></i> Nonaktifkan admin
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#activeModal">
                                <i class="fas fa-save"></i> Aktifkan admin
                            </button>
                        <?php endif ?>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-save"></i> Hapus admin
                        </button>
                        <a href="<?= base_url('/admin') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?= include 'modalConfirm.php'; ?>

<?= $this->endSection('content'); ?>
