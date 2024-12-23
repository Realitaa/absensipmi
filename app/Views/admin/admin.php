<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

    <div id="table-container" class="p-3">
        <h3 class="text-center">Tabel Admin</h3>
        <div class="d-flex justify-content-between align-items-center">
            <a class="btn btn-primary my-2" href="/admin/add">Tambah</a> 
            <input class="form-control w-auto " type="search" placeholder="Search" aria-label="Search" id="search-input">
        </div>                
        <table class="table table-bordered table-striped text-center align-middle">
        <thead>
            <tr>
            <th scope="col" width="10px">No.</th>
            <th scope="col">Profil</th>
            <th scope="col">Nama</th>
            <th scope="col">Email</th>
            <th scope="col">No Telepon</th>
            <th scope="col">Status</th>
            <th scope="col">Kehadiran</th>
            <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
                <?php foreach ($admins as $a):  ?>
                    <tr height="200px">
                        <th scope="row"><?= esc($k['id']); ?></th>
                        <td width="200px"><?= esc($k['foto']); ?></td>
                        <td><?= esc($a['nama']); ?></td>
                        <td><?= esc($a['email']); ?></td>
                        <td><?= esc($a['no_telepon']); ?></td>
                        <td><?= esc($a['status']); ?></td>
                    </tr>
                <?php endforeach ?>
        </tbody>
        </table>
    </div>

<?= $this->endSection('content'); ?>