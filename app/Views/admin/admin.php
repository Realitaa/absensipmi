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
            <th scope="col">Nama Pengguna</th>
            <th scope="col">Email</th>
            <th scope="col">No Telepon</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                    $i = 1;  
                    foreach ($admins as $a):  ?>
                    <tr class="<?= (esc($k['status']) == 'nonaktif') ? 'table-warning' : ''; ?>">
                        <th scope="row"><?= $i++; ?></th>
                        <td>
                            <?php if (!empty($a['foto'])): ?>
                                <img src="<?= esc($a['foto']); ?>" alt="Foto <?= esc($k['nama']); ?>" width="100" height="100">
                            <?php else: ?>
                                <span>Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($a['nama']); ?></td>
                        <td><?= esc($a['nama_pengguna']); ?></td>
                        <td><?= esc($a['email']); ?></td>
                        <td><?= esc($a['no_telepon']); ?></td>
                        <td><?= esc($a['status']); ?></td>
                        <td><a href="/admin/edit/<?= $a['id']; ?>" class="btn btn-info">Edit</a></td>
                    </tr>
                <?php endforeach ?>
        </tbody>
        </table>
    </div>

<?= $this->endSection('content'); ?>