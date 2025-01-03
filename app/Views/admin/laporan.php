<?= $this->extend('template/navbarAdmin'); ?>

<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="bg-light">
<div id="table-container" class="p-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="">Tabel Absensi Harian</h3>
            <p>Tanggal: <input type="text" id="tanggal" name="tanggal" placeholder="Pilih tanggal" value="<?= date('d-m-Y'); ?>"></p>
        </div>
        <button class="btn btn-success" id="export-harian"><i class="bi bi-filetype-xlsx" style="font-size: 20px;"></i>    Export to xlsx</button>
    </div>
    <table class="table table-bordered table-striped text-center align-middle" id="tabel-harian">
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
    <tbody id="daily-table-body">
        <!-- Data absensi harian diisi dari script AJAX -->
    </tbody>
</table>

</div>

    <div id="table-container" class="p-3">
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="">Tabel Absensi Bulanan</h3>
                <p>Bulan: <input type="text" id="bulan" name="bulan" placeholder="Pilih bulan" value="<?= date('m-Y', strtotime('-1 day')); ?>"></p>
            </div>
            <button class="btn btn-success" id="export-bulanan"><i class="bi bi-filetype-xlsx" style="font-size: 20px;"></i>    Export to xlsx</button>
        </div>
        
        <table class="table table-bordered table-striped text-center align-midle" id="tabel-bulanan">
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
        <tbody class="monthly-table-body">
            <!-- Data absensi bulanan diisi dari script AJAX -->
        </tbody>
        </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Flatpickr untuk tanggal dan bulan
    flatpickr('#tanggal', {
        dateFormat: 'd-m-Y',
        minDate: '<?= esc($min_date); ?>',
        maxDate: '<?= esc($max_date); ?>',
        onChange: function (selectedDates, dateStr) {
            loadDailyData(dateStr);
        }
    });

    flatpickr('#bulan', {
        dateFormat: 'm-Y',
        minDate: '<?= esc($min_month); ?>',
        maxDate: '<?= esc($max_month); ?>',
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "m-Y", altFormat: "F Y" })],
        onChange: function (selectedDates, dateStr) {
            loadMonthlyData(dateStr);
        }
    });

    // Fungsi untuk memuat data tabel harian
    function loadDailyData(tanggal) {
        const tableBody = document.getElementById('daily-table-body');
        const exportButton = document.getElementById('export-harian');

        // Tampilkan animasi loading dan nonaktifkan tombol export
        tableBody.innerHTML = `<tr><td colspan="7">Loading...</td></tr>`;
        exportButton.classList.remove('btn-success');
        exportButton.classList.add('btn-secondary');
        exportButton.disabled = true;

        // Lakukan fetch untuk mengambil data
        fetch(`<?= site_url('/absensipmi/administrator/laporan/getHarianData'); ?>?tanggal=${tanggal}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = ''; // Hapus baris loading

                if (data.length > 0) {
                    data.forEach((k, index) => {
                        const row = `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${k.nama}</td>
                                <td>${k.jabatan}</td>
                                <td>${k.status}</td>
                                <td>${k.kehadiran || 'Tanpa Keterangan'}</td>
                                <td>${k.tanggal}</td>
                                <td>${k.jam || '-'}</td>
                            </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    tableBody.innerHTML = `<tr><td colspan="7">Tidak Ada Data untuk Tanggal Ini</td></tr>`;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                tableBody.innerHTML = `<tr><td colspan="7">Terjadi Kesalahan Saat Mengambil Data</td></tr>`;
            })
            .finally(() => {
                // Aktifkan kembali tombol export
                exportButton.classList.remove('btn-secondary');
                exportButton.classList.add('btn-success');
                exportButton.disabled = false;
            });
    }

    // Fungsi untuk memuat data tabel bulanan
    function loadMonthlyData(bulan) {
        const tableBody = document.querySelector('.monthly-table-body');
        const exportButton = document.getElementById('export-bulanan');

        // Tampilkan animasi loading dan nonaktifkan tombol export
        tableBody.innerHTML = `<tr><td colspan="36">Loading...</td></tr>`;
        exportButton.classList.remove('btn-success');
        exportButton.classList.add('btn-secondary');
        exportButton.disabled = true;

        // Lakukan AJAX untuk mengambil data
        $.ajax({
            url: '<?= base_url('/absensipmi/administrator/laporan/getBulananData'); ?>',
            type: 'GET',
            data: { bulan: bulan },
            success: function (data) {
                tableBody.innerHTML = ''; // Hapus baris loading

                if (data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="36">Tidak ada data untuk bulan ini.</td></tr>`;
                    return;
                }

                data.forEach((item, index) => {
                    let row = `<tr>
                        <th scope="row">${index + 1}</th>
                        <td>${item.nama}</td>`;

                    let summary = { H: 0, S: 0, C: 0, TK: 0 };

                    for (let i = 1; i <= 30; i++) {
                        let absensi = item.absensi[i] || '';
                        let abbreviation = '';

                        // Memeriksa apakah absensi adalah 'Tanpa Keterangan'
                        if (absensi === 'Tanpa Keterangan') {
                            abbreviation = 'TK';
                        } else {
                            abbreviation = absensi.charAt(0).toUpperCase();
                        }

                        row += `<td>${abbreviation}</td>`;

                        // Menambahkan ke summary berdasarkan abbreviation
                        if (abbreviation === 'H') summary.H++;
                        if (abbreviation === 'S') summary.S++;
                        if (abbreviation === 'C') summary.C++;
                        if (abbreviation === 'TK') summary.TK++;
                    }

                    row += `
                        <td>${summary.H}</td>
                        <td>${summary.S}</td>
                        <td>${summary.C}</td>
                        <td>${summary.TK}</td>
                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                tableBody.innerHTML = `<tr><td colspan="36">Terjadi Kesalahan Saat Mengambil Data</td></tr>`;
            },
            complete: function () {
                // Aktifkan kembali tombol export
                exportButton.classList.remove('btn-secondary');
                exportButton.classList.add('btn-success');
                exportButton.disabled = false;
            }
        });
    }

    // Panggil fungsi untuk memuat data saat halaman dimuat
    loadDailyData("<?= date('d-m-Y'); ?>");
    loadMonthlyData("<?= date('m-Y'); ?>");
});

    // Ekspor Tabel Harian ke Excel
    document.getElementById('export-harian').addEventListener('click', function () {
        const table = document.getElementById('tabel-harian');
        let sheetName = document.getElementById('tanggal').value;
        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: sheetName }); // Mengambil seluruh tabel
            XLSX.writeFile(workbook, 'laporan_absensi ' + sheetName + '.xlsx');
        } else {
            console.error('Tabel tidak ditemukan!');
        }
    });
    
    // Ekspor Tabel Bulanan ke Excel
    document.getElementById('export-bulanan').addEventListener('click', function () {
        const table = document.getElementById('tabel-bulanan');
        let sheetName = document.getElementById('bulan').value;
        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: sheetName }); // Mengambil seluruh tabel
            XLSX.writeFile(workbook, 'laporan_absensi ' + sheetName + '.xlsx');
        } else {
            console.error('Tabel tidak ditemukan!');
        }
    });
</script>

<?= $this->endSection('content'); ?>