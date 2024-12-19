<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Karyawan</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/addKaryawan') ?>" method="post">
                <!-- Nama Pengguna -->
                <div class="form-group">
                    <label for="nama" class="text-primary">Nama Pengguna</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama pengguna" required>
                </div>

                <!-- Email -->
                <div class="form-group mt-3">
                    <label for="email" class="text-primary">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email pengguna" required>
                </div>

                <!-- Nomor Telepon -->
                <div class="form-group mt-3">
                    <label for="telepon" class="text-primary">Nomor Telepon</label>
                    <input type="tel" name="telepon" id="telepon" class="form-control" placeholder="Masukkan nomor telepon" required>
                </div>

                <!-- Jabatan -->
                <div class="form-group mt-3">
                    <label for="jabatan" class="text-primary">Jabatan</label>
                    <select name="jabatan" id="jabatan" class="form-control" required>
                        <option value="" disabled selected>Pilih jabatan</option>
                        <option value="Manager">Manager</option>
                        <option value="Supervisor">Supervisor</option>
                        <option value="Staff">Staff</option>
                        <option value="Intern">Intern</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-user-plus"></i> Tambah Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

<?= $this->endSection('content'); ?>