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
        <!-- Data absensi harian diisi dari script AJAX -->
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
            minDate: '<?= esc($min_date); ?>',
            maxDate: '<?= esc($max_date); ?>',
            onChange: function(selectedDates, dateStr, instance) {
                loadData(dateStr); // Memuat data baru saat tanggal berubah
            }
        });

        // Fungsi untuk memuat data berdasarkan tanggal yang dipilih
        function loadData(tanggal) {
            fetch("<?= site_url('laporan/getHarianData'); ?>?tanggal=" + tanggal)
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById('table-body');
                    tableBody.innerHTML = ''; // Hapus data sebelumnya

                    // Periksa jika ada data
                    if (data.length > 0) {
                        data.forEach((k, index) => {
                            let row = document.createElement('tr');

                            // Menambahkan data ke dalam baris
                            row.innerHTML = `
                                <th scope="row">${index + 1}</th>
                                <td>${k.nama}</td>
                                <td>${k.jabatan}</td>
                                <td>${k.status}</td>
                                <td>${k.kehadiran || 'Tanpa Keterangan'}</td>
                                <td>${k.tanggal}</td>
                                <td>${k.jam || '-'}</td>
                            `;

                            tableBody.appendChild(row);
                        });
                    } else {
                        let row = document.createElement('tr');
                        row.innerHTML = '<td colspan="7">Tidak Ada data untuk tanggal ini</td>';
                        tableBody.appendChild(row);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Memuat data berdasarkan default tanggal saat halaman pertama kali dimuat
        loadData("<?= date('d-m-Y', strtotime('-1 day')); ?>");
    });
</script>

<?= $this->endSection('content'); ?>