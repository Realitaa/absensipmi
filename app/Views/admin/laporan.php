<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="bg-light">
<div id="table-container" class="p-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3 class="">Tabel Absensi Harian</h3>
            <p>Tanggal: <input type="text" id="tanggal" name="tanggal" placeholder="Pilih tanggal" value="<?= date('d-m-Y', strtotime('-1 day')); ?>"></p>
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
            <!-- Data absensi harian diisi dari script AJAX -->
        </tbody>
        </table>
    </div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
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
                    let tableBody = document.getElementById('daily-table-body');
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

        // Inisialisasi Datepicker untuk bulan
        flatpickr('#bulan', {
            dateFormat: 'm-Y',
            minDate: '<?= esc($min_month); ?>',
            maxDate: '<?= esc($max_month); ?>',
            plugins: [
            new monthSelectPlugin({
                shorthand: true,
                dateFormat: "m-Y",
                altFormat: "F Y"
            })
        ],
        });

        // Fungsi untuk mengambil dan memuat data ke tabel
        function loadTableData(bulan) {
            $.ajax({
                url: '<?= base_url('laporan/getBulananData'); ?>', // Sesuaikan dengan URL endpoint Anda
                type: 'GET',
                data: { bulan: bulan },
                success: function (data) {
                    const tbody = $('.monthly-table-body');
                    tbody.empty(); // Kosongkan tabel sebelum mengisi ulang

                    if (data.length === 0) {
                        tbody.append('<tr><td colspan="36">Tidak ada data untuk bulan ini.</td></tr>');
                        return;
                    }

                    data.forEach((item, index) => {
                        let row = `<tr>
                            <th scope="row">${index + 1}</th>
                            <td>${item.nama}</td>`;

                        // Inisialisasi counter untuk summary
                        let summary = { H: 0, S: 0, C: 0, TK: 0 };

                        // Tambahkan kolom absensi (1-30)
                        for (let i = 1; i <= 30; i++) {
                            const absensi = item.absensi[i] || ''; // Ambil tipe absensi

                            // Logika untuk akronim huruf kapital
                            let abbreviation = '';
                            if (absensi.toLowerCase() === 'tanpa keterangan') {
                                abbreviation = 'TK'; // Akronim khusus untuk Tanpa Keterangan
                                summary.TK++;
                            } else {
                                abbreviation = absensi.charAt(0).toUpperCase(); // Huruf kapital pertama
                                if (abbreviation === 'H') summary.H++;
                                if (abbreviation === 'S') summary.S++;
                                if (abbreviation === 'C') summary.C++;
                            }

                            row += `<td>${abbreviation}</td>`;
                        }

                        // Tambahkan kolom summary
                        row += `
                            <td>${summary.H}</td>
                            <td>${summary.S}</td>
                            <td>${summary.C}</td>
                            <td>${summary.TK}</td>
                        </tr>`;
                        tbody.append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        // Panggil fungsi untuk pertama kali dengan bulan default
        const defaultBulan = $('#bulan').val();
        loadTableData(defaultBulan);

        // Update data tabel saat bulan diubah
        $('#bulan').change(function () {
            const bulan = $(this).val();
            loadTableData(bulan);
        });
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