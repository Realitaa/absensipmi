<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="bg-light">
<div id="table-container" class="p-3">
    <h3 class="">Tabel Absensi Harian</h3>
    <p>Tanggal: <input type="text" id="tanggal" name="tanggal" placeholder="Pilih tanggal" value="<?= date('d-m-Y', strtotime('-1 day')); ?>"></p>
    

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
                foreach ($karyawan as $k): 
                          if ($k['hadir_id'] !== null) {
                            $k['absensi'] = 'Hadir';
                        } elseif ($k['sakit_id'] !== null) {
                            $k['absensi'] = 'Sakit';
                        } elseif ($k['cuti_id'] !== null) {
                            $k['absensi'] = 'Cuti';
                        } elseif ($k['tanpaketerangan_id'] !== null) {
                            $k['absensi'] = 'Tanpa Keterangan';
                        }
            ?>
            <tr>
                <th scope="row" class="row-number"></th>
                <td><?= esc($k['nama']); ?></td>
                <td><?= esc($k['jabatan']); ?></td>
                <td><?= esc($k['status']); ?></td>
                <td><?= esc($k['absensi']); ?></td> <!-- Gunakan variabel absensi -->
                <td><?= esc($k['tanggal']); ?></td> <!-- Menampilkan tanggal sesuai format DD-MM-YYYY -->
                <td><?= esc($k['jam']) ? : "-"; ?></td>
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
        <h3 class="">Tabel Absensi Bulanan</h3>
        <?php
        $months = [
            1 => 'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        ?>
        <p>Bulan: 
        <select name="month" id="month">
            <?php foreach ($months as $iM => $month) {
                echo "<option value='$month'>" . $month . "</option>";
            }; ?>
        </select>
        </p>

        <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th scope="col" width="10px" rowspan="2">No.</th>
                <th scope="col" width="400px" rowspan="2">Nama</th>
                <?php for ($i = 1;$i<=30;$i++): ?>
                    <th scope="col" rowspan="2"><?= $i; ?></th>
                <?php endfor ?>
                <th colspan="4">Summary</th>
            </tr>
            <tr>
                <th scope="col">H</th>
                <th scope="col">S</th>
                <th scope="col">C</th>
                <th scope="col">TK</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Reza</td>
            <td>H</td>
            <td>S</td></td>
            <td>C</td>
            <td>TK</td>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
       

        // Inisialisasi Flatpickr
        flatpickr('#tanggal', {
            dateFormat: 'd-m-Y',

        });
    });
</script>

<?= $this->endSection('content'); ?>