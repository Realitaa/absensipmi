<?= $this->extend('template/navbarKaryawan') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<div class="container pt-4">
    <?php if (isset($absensi['tipe']) && $absensi['tipe'] == 'Hadir'): ?>
        <h1 class="text-center">Kamu sudah absen hari ini</h1>
        <h3 class="text-center">Tipe: Hadir</h3>
        <p class="text-center pb-3">Selamat Bekerja</p>

    <?php elseif (isset($absensi['tipe']) && $absensi['tipe'] == 'Sakit'): ?>
        <?php if (esc($absensi['statusSakit']) == 'Menunggu') : ?>
            <h1 class="text-center">Form Ketidakhadiran menunggu di konfirmasi admin.</h1>
            <h3 class="text-center">Tipe: Sakit</h3>
            <h6 class="text-center"><?= esc($absensi['judulSakit']); ?></h6>
            <p class="text-center pb-3"><?= esc($absensi['deskripsiSakit']); ?></p>
        <?php elseif (esc($absensi['statusSakit']) == 'Terima') : ?>
            <h1 class="text-center">Kamu sudah absen hari ini.</h1>
            <h3 class="text-center">Tipe: Sakit</h3>
            <p class="text-center pb-3">Semoga cepat sembuh ya.</p>
        <?php elseif (esc($absensi['statusSakit']) == 'Tolak') : ?>
            <h1 class="text-center">Maaf, form ketidakhadiran ditolak.</h1>
            <p class="text-center pb-3">Hubungi admin untuk meminta informasi</p>
        <?php endif; ?>

    <?php elseif (isset($absensi['tipe']) && $absensi['tipe'] == 'Cuti') : ?>
        <?php if (esc($absensi['statusCuti']) == 'Menunggu') : ?>
            <h1 class="text-center">Form Ketidakhadiran menunggu di konfirmasi admin.</h1>
            <h3 class="text-center">Tipe: Cuti</h3>
            <h6 class="text-center"><?= esc($absensi['judulCuti']); ?></h6>
            <p class="text-center pb-3"><?= esc($absensi['deskripsiCuti']); ?></p>
        <?php elseif (esc($absensi['statusCuti']) == 'Terima') : ?>
            <h1 class="text-center">Kamu sudah absen hari ini.</h1>
            <h3 class="text-center">Tipe: Cuti</h3>
            <p class="text-center pb-3">Selamat Liburan.</p>
        <?php elseif (esc($absensi['statusCuti']) == 'Tolak') : ?>
            <h1 class="text-center">Maaf, form ketidakhadiran ditolak.</h1>
            <p class="text-center pb-3">Hubungi admin untuk meminta informasi</p>
        <?php endif; ?>

    <?php else : ?>
        <h1 class="text-center">Kamu belum absen hari ini.</h1>
        <p class="text-center">Ayo mulai absen di depan komputer admin!</p>
        <div class="text-center pb-3">
            <?php if (session('user_data')['isSecureAccount']) : ?>
                <a href="kehadiran" class="btn btn-primary me-2">Mulai Absensi</a>
                <button class="btn btn-primary" id="form_ketidakhadiran">Form Ketidakhadiran</button>
            <?php else : ?>
                <p>Atur kata sandi mu di <a href="/karyawan/me">Profil</a> agar bisa melakukan absensi.</p>
            <?php endif; ?>
            </div>
    <?php endif; ?>

    <!-- Form Container -->
    <div id="formContainer" style="display: none;">
        <div class="container py-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Formulir Ketidakhadiran</h4>
                </div>
                <div class="card-body">
                    <p>Jika tidak dapat berhadir, isi formulir berikut. Jelaskan masalah Anda agar formulir ini dapat diterima. Terima kasih atas pemberitahuannya.</p>
                    <form action="<?= base_url('/ketidakhadiran') ?>" method="post">
                        <div class="form-group">
                            <label for="tipe" class="text-primary">Tipe</label>
                            <br>
                            <input type="radio" name="tipe" id="sakit" value="Sakit">
                            <label for="sakit">Sakit</label><br>
                            <input type="radio" name="tipe" id="cuti" value="Cuti">
                            <label for="cuti">Cuti</label><br>
                            <?php if (session('errors.tipe')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.tipe') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="waktu" class="text-primary">Waktu Ketidakhadiran</label><br>
                            <input type="text" id="waktu" name="waktu" placeholder="Pilih jarak waktu" style="width: 100%;">
                            <?php if (session('errors.waktu')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.waktu') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="judul" class="text-primary">Judul</label>
                            <input type="text" name="judul" id="judul" class="form-control <?= session('errors.judul') ? 'is-invalid' : '' ?>" 
                            placeholder="Contoh: Liburan ke ..." value="<?= old('judul') ?>" required>
                            <?php if (session('errors.judul')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.judul') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mt-3">
                            <label for="deskripsi" class="text-primary">Deskripsi</label>
                            <textarea name="deskripsi" id="deskripsi" class="form-control <?= session('errors.deskripsi') ? 'is-invalid' : '' ?>" 
                            placeholder="Contoh: Saya tidak dapat hadir karena ..." value="<?= old('deskripsi') ?>" rows="4" required></textarea>
                            <?php if (session('errors.deskripsi')) : ?>
                                <div class="invalid-feedback">
                                    <?= session('errors.deskripsi') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.getElementById('form_ketidakhadiran').addEventListener('click', function() {
    const formContainer = document.getElementById('formContainer');
    formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';

    // Inisialisasi Flatpickr saat elemen ditampilkan
    if (formContainer.style.display === 'block') {
        flatpickr('#waktu', {
            dateFormat: 'd-m-Y',
            mode: "range",
            minDate: "today",
        });
    }
});
</script>

<?= $this->endSection('content') ?>
