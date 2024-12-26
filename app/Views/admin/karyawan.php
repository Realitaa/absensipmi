<?= $this->extend('template/navbarAdmin'); ?>

<?= $this->section('content'); ?>

    <div id="table-container" class="p-3">
        <h3 class="text-center">Tabel Karyawan</h3>
        <div class="d-flex justify-content-between align-items-center">
            <a class="btn btn-primary my-2" href="/administrator/karyawan/add">Tambah</a> 
            <input class="form-control w-auto " type="search" placeholder="Search" aria-label="Search" id="search-input">
        </div>
        <table class="table table-bordered table-striped text-center align-middle" id="karyawan-table">
        <thead>
            <tr>
            <th scope="col" width="10px">No.</th>
            <th scope="col">Profil</th>
            <th scope="col">Nama</th>
            <th scope="col">Nama Pengguna</th>
            <th scope="col">Email</th>
            <th scope="col">No Telepon</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Status</th>
            <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($karyawan)): ?>
                <?php 
                    $i = 1; 
                    foreach ($karyawan as $k): 
                    ?>
                    <tr class="<?= (esc($k['status']) == 'nonaktif') ? 'table-warning' : ''; ?>">
                        <th scope="row"><?= $i++; ?></th>
                        <td>
                            <?php if (!empty($k['foto'])): ?>
                                <img src="<?= esc($k['foto']); ?>" alt="Foto <?= esc($k['nama']); ?>" width="100" height="100">
                            <?php else: ?>
                                <span>Tidak ada foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($k['nama']); ?></td>
                        <td><?= esc($k['nama_pengguna']); ?></td>
                        <td><?= esc($k['email']); ?></td>
                        <td><?= esc($k['no_telepon']); ?></td>
                        <td><?= esc($k['jabatan']); ?></td>
                        <td><?= esc($k['status']); ?></td>
                        <td><a href="/karyawan/edit/<?= $k['id']; ?>" class="btn btn-info">Edit</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <td colspan="7">Tidak Ada Karyawan</td>
            <?php endif ?>
        </tbody>
        </table>
    </div>

<script>
    // Skrip untuk mencari data di dalam tabel berdasarkan query pada elemen input search
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search-input');
        const table = document.getElementById('karyawan-table');
        const rows = table.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function () {
            const query = this.value.toLowerCase();

            rows.forEach(row => {
                // Gabungkan semua teks dalam satu baris menjadi satu string
                const rowText = Array.from(row.querySelectorAll('td, th'))
                    .map(cell => cell.textContent.toLowerCase())
                    .join(' ');

                // Tampilkan atau sembunyikan baris berdasarkan apakah query ditemukan
                if (rowText.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

<?= $this->endSection('content'); ?>