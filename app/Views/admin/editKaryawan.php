<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-white">
            <h4>Edit Data Karyawan</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/updateKaryawan/' . $karyawan['id']) ?>" method="post">
                <!-- Nama Pengguna -->
                <div class="form-group">
                    <label for="nama" class="text-warning">Nama Pengguna</label>
                    <input type="text" name="nama" id="nama" class="form-control" 
                        value="<?= $karyawan['nama'] ?>" placeholder="Masukkan nama pengguna" required>
                </div>

                <!-- Email -->
                <div class="form-group mt-3">
                    <label for="email" class="text-warning">Email</label>
                    <input type="email" name="email" id="email" class="form-control" 
                        value="<?= $karyawan['email'] ?>" placeholder="Masukkan email pengguna" required>
                </div>

                <!-- Nomor Telepon -->
                <div class="form-group mt-3">
                    <label for="telepon" class="text-warning">Nomor Telepon</label>
                    <input type="tel" name="telepon" id="telepon" class="form-control" 
                        value="<?= $karyawan['telepon'] ?>" placeholder="Masukkan nomor telepon" required>
                </div>

                <!-- Jabatan -->
                <div class="form-group mt-3">
                    <label for="jabatan" class="text-warning">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control" required>
                        <option value="Manager" <?= $karyawan['jabatan'] == 'Manager' ? 'selected' : '' ?>>Manager</option>
                        <option value="Supervisor" <?= $karyawan['jabatan'] == 'Supervisor' ? 'selected' : '' ?>>Supervisor</option>
                        <option value="Staff" <?= $karyawan['jabatan'] == 'Staff' ? 'selected' : '' ?>>Staff</option>
                        <option value="Intern" <?= $karyawan['jabatan'] == 'Intern' ? 'selected' : '' ?>>Intern</option>
                    </select>
                </div>

                <!-- Tombol Update -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Data
                    </button>
                    <a href="<?= base_url('admin/karyawanList') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection('content'); ?>
