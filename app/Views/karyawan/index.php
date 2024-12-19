<?= $this->extend('template/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('karyawan/navbar') ?>

<div class="container mt-4 text-light">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <h1 class="text-center">
        <?= $statusAbsensi ? "Kamu Sudah Absensi Hari Ini" : "Kamu Belum Absen Hari Ini" ?>
    </h1>
    <p class="text-center">
        <?= $statusAbsensi ? "Selamat Bekerja" : "Ayo mulai absen di depan komputer admin" ?>
    </p>
    <div class="text-center">
        <a href="/karyawan/izin" class="btn btn-primary">Ajukan Izin</a>
        <a href="/karyawan/cuti" class="btn btn-warning">Ajukan Cuti</a>
        <a href="/karyawan/riwayat" class="btn btn-info">Lihat Riwayat</a>
    </div>
</div>

<?= $this->endSection('content'); ?>