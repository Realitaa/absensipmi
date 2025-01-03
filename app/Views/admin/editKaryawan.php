<?= $this->extend('template/navbarAdmin'); ?>

<?= $this->section('content'); ?>

    <div class="container py-3">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4>Edit Data Karyawan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="d-flex flex-column align-items-center">
                            <img src="<?= !empty(esc($karyawan['foto'])) ? '/userProfile/' . esc($karyawan['foto']) : '/user.png' ; ?>" alt="Foto <?= esc($karyawan['nama']); ?>" width="300" height="300">
                            <?php if (!empty(esc($karyawan['foto']))) : ?>
                                <form action="/administrator/karyawan/reset/avatar" method="post">
                                    <input type="hidden" name="id" value="<?= esc($karyawan['id']); ?>">
                                    <button type="submit" class="btn btn-danger mt-3">Reset Avatar</button>
                                </form>
                            <?php endif ?>
                        </div>
                    </div>
                    <div class="col">
                        <form action="<?= base_url('/administrator/karyawan/update/' . $karyawan['id']) ?>" method="post">
                            <!-- Nama Pengguna -->
                            <div class="form-group">
                                <label for="nama_lengkap" class="text-warning">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control <?= session('errors.nama_lengkap') ? 'is-invalid' : '' ?>" 
                                    value="<?= old('nama_lengkap') ?: $karyawan['nama'] ?>" placeholder="Masukkan nama lengkap (required)" required>
                                    <?php if (session('errors.nama_lengkap')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.nama_lengkap') ?>
                                        </div>
                                    <?php endif; ?>
                            </div>
            
                            <!-- Nama Pengguna -->
                            <div class="form-group mt-3">
                                <label for="nama_pengguna" class="text-warning">Nama Pengguna</label>
                                <input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control <?= session('errors.nama_pengguna') ? 'is-invalid' : '' ?>" 
                                    value="<?= old('nama_pengguna') ?: $karyawan['nama_pengguna'] ?>" placeholder="Masukkan nama pengguna (required)" required>
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
                                    value="<?= old('email') ?: $karyawan['email'] ?>" placeholder="Masukkan email pengguna">
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
                                    value="<?= old('telepon') ?: $karyawan['no_telepon'] ?>" placeholder="Masukkan nomor telepon">
                                    <?php if (session('errors.telepon')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.telepon') ?>
                                        </div>
                                    <?php endif; ?>
                            </div>
            
                            <!-- Jabatan -->
                            <div class="form-group mt-3">
                                <label for="jabatan" class="text-warning">Jabatan</label>
                                <input type="jabatan" name="jabatan" id="jabatan" class="form-control <?= session('errors.jabatan') ? 'is-invalid' : '' ?>" 
                                    value="<?= old('jabatan') ?: $karyawan['jabatan'] ?>" placeholder="Masukkan jabatan">
                                    <?php if (session('errors.jabatan')) : ?>
                                        <div class="invalid-feedback">
                                            <?= session('errors.jabatan') ?>
                                        </div>
                                    <?php endif; ?>
                            </div>
        
                            <!-- Tombol Update -->
                            <div class="mt-4">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                                <?php if (esc($karyawan['status']) === 'aktif'): ?>
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#nonactiveModal">
                                        <i class="fas fa-save"></i> Nonaktifkan Karyawan
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#activeModal">
                                        <i class="fas fa-save"></i> Aktifkan Karyawan
                                    </button>
                                <?php endif ?>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#resetModal">
                                    <i class="fas fa-save"></i> Reset Password
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-save"></i> Hapus Karyawan
                                </button>
                                <a href="<?= base_url('/administrator/karyawan') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
                    </div>
                </div>

<?php include 'modalConfirm.php'; ?>

<?= $this->endSection('content'); ?>
