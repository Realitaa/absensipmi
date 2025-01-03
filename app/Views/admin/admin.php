<?= $this->extend('template/navbarAdmin'); ?>

<?= $this->section('content'); ?>

    <div id="table-container" class="p-3">
        <h3 class="text-center">Tabel Admin</h3>
        <div class="d-flex justify-content-between align-items-center">
            <a class="btn btn-primary my-2" href="admin/add">Tambah</a> 
            <input class="form-control w-auto " type="search" placeholder="Search" aria-label="Search" id="search-input">
        </div>                
        <table class="table table-bordered table-striped text-center align-middle" id="karyawan-table">
        <thead>
            <tr>
            <th scope="col" width="10px">No.</th>
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
                    <tr class="<?= (esc($a['status']) == 'nonaktif') ? 'table-warning' : ''; ?>">
                        <th scope="row"><?= $i++; ?></th>
                        <td><?= esc($a['nama']); ?></td>
                        <td><?= esc($a['nama_pengguna']); ?></td>
                        <td><?= esc($a['email']); ?></td>
                        <td><?= esc($a['no_telepon']); ?></td>
                        <td><?= esc($a['status']); ?></td>
                        <td><a href="admin/edit/<?= $a['id']; ?>" class="btn btn-info">Edit</a></td>
                    </tr>
                <?php endforeach ?>
        </tbody>
        </table>
    </div>

<script src="/search-input.js"></script>

<?= $this->endSection('content'); ?>