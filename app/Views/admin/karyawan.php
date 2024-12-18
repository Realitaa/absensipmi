<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="bg-light">
    <div id="table-container" class="p-3">
        <h3 class="text-center">Tabel Karyawan</h3>
        <table class="table table-bordered table-striped text-center align-middle">
        <thead>
            <tr>
            <th scope="col" width="10px">No.</th>
            <th scope="col">Profil</th>
            <th scope="col">Nama</th>
            <th scope="col">Email</th>
            <th scope="col">No Telepon</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Status</th>
            <th scope="col">Kehadiran</th>
            <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($karyawan)): ?>
                <?php foreach ($karyawan as $k):  ?>
                    <tr height="200px">
                        <th scope="row"><?= esc($k['id']); ?></th>
                        <td width="200px"><?= esc($k['foto']); ?></td>
                        <td><?= esc($k['nama']); ?></td>
                        <td><?= esc($k['email']); ?></td>
                        <td><?= esc($k['no_telepon']); ?></td>
                        <td><?= esc($k['jabatan']); ?></td>
                        <td><?= esc($k['status']); ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else : ?>
                <td colspan="7">Tidak Ada Karyawan</td>
            <?php endif ?>
        </tbody>
        </table>
    </div>
</div>

<?= $this->endSection('content'); ?>