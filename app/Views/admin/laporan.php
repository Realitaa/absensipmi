<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-light">
<div id="table-container" class="p-3">
    <h3 class="">Tabel Kehadiran Harian</h3>
    <p>Tanggal: <input type="text" id="tanggal" name="tanggal" placeholder="Pilih tanggal" value="<?= date('d-m-Y'); ?>" ></p>
    

    <table class="table table-bordered table-striped text-center align-middle">
    <thead>
        <tr>
            <th scope="col" width="10px">No.</th>
            <th scope="col">Nama</th>
            <th scope="col">Jabatan</th>
            <th scope="col">Status</th>
            <th scope="col">Kehadiran</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Waktu</th>
        </tr>
    </thead>
    <tbody id="table-body">
        <?php if (!empty($karyawan)): ?>
            <?php 
                $i = 1; 
                foreach ($karyawan as $k): 
                    // Filter berdasarkan tanggal (menampilkan hanya yang tanggalnya sama dengan hari ini)
 // Menggunakan format yang sudah sesuai dengan query
                        // Tentukan absensi
                         // Default

                         if ($k['absensi_status'] === 0) {
                            // Tidak ada kehadiran di hadir, sakit, atau cuti, maka beri status 'Tanpa Keterangan'
                            $k['absensi'] = 'Tanpa Keterangan';
                        } elseif ($k['hadir_id'] !== null) {
                            $k['absensi'] = 'Hadir';
                        } elseif ($k['sakit_id'] !== null) {
                            $k['absensi'] = 'Sakit';
                        } elseif ($k['cuti_id'] !== null) {
                            $k['absensi'] = 'Cuti';
                        }
            ?>
            <tr>
                <th scope="row"><?= $i++; ?></th>
                <td><?= esc($k['nama']); ?></td>
                <td><?= esc($k['jabatan']); ?></td>
                <td><?= esc($k['status']); ?></td>
                <td><?= esc($k['absensi']); ?></td> <!-- Gunakan variabel absensi -->
                <td><?= esc($k['tanggal']); ?></td> <!-- Menampilkan tanggal sesuai format DD-MM-YYYY -->
                <td><?= esc($k['jam']); ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="7">Tidak Ada Karyawan</td>
            </tr>
        <?php endif ?>
    </tbody>
</table>

</div>

    <div id="table-container" class="p-3">
        <h3 class="">Tabel Kehadiran Mingguan</h3>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" width="10px" rowspan="2">No.</th>
                <th scope="col" width="400px" rowspan="2">Nama</th>
                <th scope="col" rowspan="2">Senin</th>
                <th scope="col" rowspan="2">Selasa</th>
                <th scope="col" rowspan="2">Rabu</th>
                <th scope="col" rowspan="2">Kamis</th>
                <th scope="col" rowspan="2">Jumat</th>
                <th scope="col" rowspan="2">Sabtu</th>
                <th scope="col" colspan="4">Summary</th>
            </tr>
            <tr>
                <th>H</th>
                <th>S</th>
                <th>C</th>
                <th>TP</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Reza</td>
            <td>Hadir</td>
            <td>Hadir</td>
            <td>Hadir</td>
            <td>Hadir</td>
            <td>Hadir</td>
            <td>Hadir</td>
            </tr>
        </tbody>
        </table>
    </div>

    <div id="table-container" class="p-3">
        <h3 class="">Tabel Kehadiran Bulanan</h3>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" width="10px" rowspan="2">No.</th>
                <th scope="col" width="400px" rowspan="2">Nama</th>
                <th scope="col">Jumlah Hadir</th>
                <th scope="col">Jumlah Sakit</th>
                <th scope="col">Jumlah Cuti</th>
                <th scope="col">Jumlah Tanpa Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Reza</td>
            <td>100</td>
            <td>100</td></td>
            <td>100</td>
            <td>100</td>
            </tr>
        </tbody>
        </table>
    </div>

    <div id="table-container" class="p-3">
        <h3 class="">Tabel Keterlambatan</h3>
        <p>Jam masuk paling lama adalah jam 09:00 pagi.</p>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" width="10px" rowspan="2">No.</th>
                <th scope="col" width="400px" rowspan="2">Nama</th>
                <th scope="col">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Reza</td>
            <td>Hadir</td>
        </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
    $minDate = date('d-m-Y', strtotime(min(array_column($karyawan, 'tanggal'))));
    $maxDate = date('d-m-Y', strtotime(max(array_column($karyawan, 'tanggal'))));
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        flatpickr('#tanggal', {
            dateFormat: 'd-m-Y', // Format DD-MM-YYYY
            defaultDate: new Date(), // Set default ke hari ini
            minDate: "<?= esc($minDate); ?>", // Batas minimal tanggal
            maxDate: "today", // Batas maksimal tanggal
        });
    });

    document.getElementById('tanggal').addEventListener('change', function() {
        // Ambil tanggal yang dipilih
        const selectedDate = document.getElementById('tanggal').value;
        
        // Dapatkan seluruh tabel tubuh
        const rows = document.querySelectorAll('#table-body tr');

        rows.forEach(row => {
            const dateCell = row.cells[5]; // Kolom Tanggal
            if (dateCell) {
                // Jika tanggal di tabel tidak sama dengan tanggal yang dipilih, sembunyikan baris
                if (dateCell.textContent !== selectedDate) {
                    row.style.display = 'none';
                } else {
                    row.style.display = '';
                }
            }
        });
});
</script>

<?= $this->endSection('content'); ?>