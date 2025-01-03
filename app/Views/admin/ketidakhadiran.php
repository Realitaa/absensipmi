<?= $this->extend('template/navbarAdmin') ?>

<?= $this->section('content') ?>

<h3 class="text-center py-3">Pengajuan Ketidakhadiran</h3>
<?php foreach ($ketidakhadiran as $k) : ?>
    <div class="card m-3" style="width: auto;">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 d-flex justify-content-center">
                <img src="<?= !empty(esc($k['foto'])) ? '/userProfile/' . esc($k['foto']) : '/user.png' ; ?>" alt="User Profile" width="200px" height="200px">
            </div>
            <div class="col-lg">
                <h5 class="card-title"><?= esc($k['nama']); ?></h5>
                <p class="card-text"><b><?= esc($k['judul']); ?></b>: <?= esc($k['deskripsi']); ?></p>
                <a href="/absensi/<?= $k['absensiID']; ?>" class="btn btn-info">Lihat Form Ketidakhadiran</a>
            </div>
        </div>
    </div>
    </div>
<?php endforeach ?>
<?= $this->endSection('content') ?>