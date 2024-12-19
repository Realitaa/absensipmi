<?= $this->extend('template/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('karyawan/navbar') ?>

<div class="container mt-4">
    <h2>Riwayat Absensi, Izin, dan Cuti</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Status</th>
            </tr>   
        </thead>
        <tbody>
            <?php foreach ($riwayat as $item): ?>
                <tr>
                    <td><?= $item['tanggal'] ?></td>
                    <td><?= $item['jenis'] ?></td>
                    <td><?= $item['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection('content'); ?>