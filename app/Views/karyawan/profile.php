<?= session('user_data')['Role'] == 'admin' ? $this->extend('template/navbarAdmin') : $this->extend('template/navbarKaryawan'); ?>
<?= $this->section('content'); ?>

<?php 
// Menggunakan service URI untuk mendapatkan segmen URL
$uri = service('uri');

// Ambil segmen ke-2 dari URL
$segment2 = $uri->getSegment(2);
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<div class="container py-3">
    <div class="row text-center pb-3">
        <div class="col-md-5">
            <img src="<?= !empty(esc($karyawan['foto'])) ? '/userProfile/' . esc($karyawan['foto']) : '/user.png' ; ?>" alt="User Avatar" width="300px">
        </div>
        <div class="col-md-7 align-content-center">
            <h2 class="pt-2"><?= esc($karyawan['nama']); ?></h2>
            <h4><?= esc($karyawan['jabatan']); ?></h4>
            <p><i class="bi bi-envelope"></i> <?= esc($karyawan['email']); ?></p>
            <p><i class="bi bi-phone"></i> <?= esc($karyawan['no_telepon']); ?></p>
            <?= $segment2 === 'me' ? '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal">Edit Profile</button>' : '' ; ?>
        </div>
    </div>
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Tabel Absensi Bulanan</h3>
                    <p>Bulan: <input type="text" id="bulan" name="bulan" placeholder="Pilih bulan" value="<?= date('m-Y', strtotime('-1 day')); ?>"></p>
                </div>
                <p class="align-self-end">Tipe: <select id="filter-absensi">
                    <option value="semua">Semua</option>
                    <option value="hadir">Hadir</option>
                    <option value="sakit">Sakit</option>
                    <option value="cuti">Cuti</option>
                    <option value="tanpa keterangan">Tanpa Keterangan</option>
                </select></p>
                <button class="btn btn-success" id="export-bulanan"><i class="bi bi-filetype-xlsx" style="font-size: 20px;"></i>    Export to xlsx</button>
            </div>
            <table class="table text-center" id="tabel-bulanan">
                <thead>
                    <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Absensi</th>
                    <th scope="col">Detail</th>
                    </tr>
                </thead>
                <tbody class="monthly-table-body">
                    <!-- Data absensi bulanan diisi dari script AJAX -->
                </tbody>
            </table>
    </div>
</div>

<?php if ($segment2 === 'me') : ?>
<!-- Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Mengubah Profil Saya</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('/karyawan/update/me'); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="email" class="text-primary">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="Masukkan email (wajib)" value="<?= esc($karyawan['email']); ?>">
            </div>
            <div class="form-group mt-3">
                <label for="telepon" class="text-primary">Nomor Telepon (WhatsApp)</label>
                <input type="tel" name="telepon" id="telepon" class="form-control" placeholder="Masukkan nomor telepon (wajib)" value="<?= esc($karyawan['no_telepon']); ?>">
            </div>
            <div class="form-group mt-3">
                <label for="password" class="text-primary">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password (kosongkan jika tidak diubah)">
            </div>
            <div class="form-group mt-3">
                <label for="confirm_password" class="text-primary">Konfirmasi Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Konfirmasi Password (kosongkan jika tidak diubah)">
            </div>
            <div class="form-group mt-3">
                <label for="avatar" class="text-primary">Foto Profil (1:1 disarankan. <span class="text-danger">MAX file size: 1 MB</span>) </label>
                <input type="file" name="avatar" id="avatar" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if ($authority == 'admin') : ?>
<div class="modal fade" id="batalAbsenModal" tabindex="-1" aria-labelledby="batalAbsenModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="batalAbsenModalLabel">Batalkan Absensi</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="batalAbsenForm" method="post" action="/administrator/karyawan/absensi/delete">
        <div class="modal-body">
          <p>Apakah Anda yakin ingin membatalkan absensi ini?</p>
          <input type="hidden" name="id" id="absensi-id" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-danger">Ya</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
<script>
    // Inisialisasi Flatpickr
    flatpickr('#bulan', {
        dateFormat: 'm-Y',
        minDate: '<?= esc($min_month); ?>',
        maxDate: '<?= esc($max_month); ?>',
        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "m-Y", altFormat: "F Y" })],
        onChange: function (selectedDates, dateStr) {
            loadMonthlyData(dateStr);
        }
    });

    // Fungsi untuk memuat data tabel bulanan
    function loadMonthlyData(bulan) {
        const tableBody = document.querySelector('.monthly-table-body');
        const exportButton = document.getElementById('export-bulanan');

        if (!tableBody || !exportButton) {
            console.error('Tabel atau tombol tidak ditemukan.');
            return;
        }

        // Tampilkan animasi loading dan nonaktifkan tombol export
        tableBody.innerHTML = `<tr><td colspan="4">Loading...</td></tr>`;
        exportButton.classList.remove('btn-success');
        exportButton.classList.add('btn-secondary');
        exportButton.disabled = true;

        // Lakukan AJAX untuk mengambil data
        $.ajax({
            url: '<?= base_url('/laporan/getUserBulananData'); ?>',
            type: 'GET',
            data: { id: <?= esc($karyawan['id']); ?>, bulan: bulan },
            dataType: 'json',
            success: function (data) {
                tableBody.innerHTML = ''; // Hapus baris loading

                if (!data || data.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4">Tidak ada data untuk bulan ini.</td></tr>`;
                    return;
                }

                data.forEach((item, index) => {
    const waktuCell = item.tipe === 'Hadir'
        ? item.waktu || '-'
        : `<a href="/absensi/${item.id}" class="btn btn-primary">Rincian</a>`;

    const adminButton = <?= json_encode($authority === 'admin'); ?> && item.cStatus != 'Menunggu' && item.sStatus != 'Menunggu'
        ? `<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#batalAbsenModal" data-id="${item.id}">Batalkan</button>`
        : '';

    const row = `
        <tr>
            <td>${item.tanggal}</td>
            <td>${item.tipe}</td>
            <td>${waktuCell} ${adminButton}</td>
        </tr>
    `;
    tableBody.insertAdjacentHTML('beforeend', row);
});
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                tableBody.innerHTML = `<tr><td colspan="4">Terjadi Kesalahan Saat Mengambil Data</td></tr>`;
            },
            complete: function () {
                // Aktifkan kembali tombol export
                exportButton.classList.remove('btn-secondary');
                exportButton.classList.add('btn-success');
                exportButton.disabled = false;
            }
        });
    }

    // Muat data bulan sekarang secara default saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', function () {
        const defaultMonth = '<?= date("m-Y", strtotime("-1 day")); ?>';
        loadMonthlyData(defaultMonth);
    });

    document.getElementById('filter-absensi').addEventListener('change', function () {
    const selectedType = this.value.toLowerCase(); // Ambil nilai tipe yang dipilih
    const rows = document.querySelectorAll('.monthly-table-body tr'); // Ambil semua baris data di tabel

    rows.forEach(row => {
        const tipeCell = row.querySelector('td:nth-child(2)'); // Kolom tipe (kolom ke-3)
        if (tipeCell) {
            const tipeText = tipeCell.textContent.trim().toLowerCase();
            // Tampilkan baris jika tipe sesuai atau opsi "semua" dipilih
            if (selectedType === 'semua' || tipeText === selectedType) {
                row.style.display = ''; // Tampilkan baris
            } else {
                row.style.display = 'none'; // Sembunyikan baris
            }
        }
    });
});

document.getElementById('export-bulanan').addEventListener('click', function () {
        const table = document.getElementById('tabel-bulanan');
        let sheetName = document.getElementById('bulan').value;
        if (table) {
            const workbook = XLSX.utils.table_to_book(table, { sheet: sheetName }); // Mengambil seluruh tabel
            XLSX.writeFile(workbook, 'laporan_absensi ' + '<?= esc($karyawan['nama']) ?> ' + sheetName + '.xlsx');
        } else {
            console.error('Tabel tidak ditemukan!');
        }
    });

// Event listener saat modal dibuka
const batalAbsenModal = document.getElementById('batalAbsenModal');
  batalAbsenModal.addEventListener('show.bs.modal', function (event) {
    // Tombol yang memicu modal
    const button = event.relatedTarget;

    // Ambil data-id dari tombol
    const absensiId = button.getAttribute('data-id');

    // Set nilai ke input hidden di form
    const absensiInput = document.getElementById('absensi-id');
    absensiInput.value = absensiId;
  });
</script>


<?= $this->endSection('content'); ?>