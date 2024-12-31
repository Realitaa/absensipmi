<?= $this->extend('template/navbarAdmin') ?>

<?= $this->section('content') ?>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-10px);
        cursor: pointer;
    }

    .card .card-body {
        position: relative;
        z-index: 1;
    }

    .card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    }
</style>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="ps-3">Selamat Datang Administrator <?= session('user_data')['Username']; ?></h2>
                <p class="ps-3">Berikut ini adalah laporan singkat kehadiran Karyawan PMI Kota Medan hari ini (di update setiap 10 detik).</p>
            </div>
            <a href="absensi" class="btn btn-primary m-3">Mulai Barcode Absensi</a>
        </div>
    

        <div class="container px-5">
        <div class="row justify-content-center">
            <!-- Card Hadir -->
            <div class="col-6 col-sm-6 col-md-3 mb-4" onclick="showTable('hadir')">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('hadir')">
                    <div class="position-relative pt-3">
                        <img src="/user.png" class="card-img-top" alt="Karyawan Hadir" style="width: 100px;">
                        <img src="/check.png" class="position-absolute" alt="Check" style="bottom: -20px; right: -10px; width: 65px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Hadir</h2>
                        <h3 class="mb-0" style="font-size: 2rem;"><span id="hadir-count">0</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card Sakit -->
            <div class="col-6 col-sm-6 col-md-3 mb-4" onclick="showTable('sakit')">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('sakit')">
                    <div class="position-relative pt-3">
                        <img src="/thermometer.png" class="card-img-top" alt="Karyawan Sakit" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Sakit</h2>
                        <h3 class="mb-0" style="font-size: 2rem;"><span id="sakit-count">0</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card Cuti -->
            <div class="col-6 col-sm-6 col-md-3 mb-4" onclick="showTable('cuti')">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('cuti')">
                    <div class="position-relative pt-3">
                        <img src="/beach-umbrella.png" class="card-img-top" alt="Karyawan Cuti" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Cuti</h2>
                        <h3 class="mb-0" style="font-size: 2rem;"><span id="cuti-count">0</span></h3>
                    </div>
                </div>
            </div>

            <!-- Card Tanpa Keterangan -->
            <div class="col-6 col-sm-6 col-md-3 mb-4" onclick="showTable('tanpaKeterangan')">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('tanpaKeterangan')">
                    <div class="position-relative">
                        <i class="bi bi-question-lg text-danger" style="font-size: 80px;"></i>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4>Tanpa Keterangan</h4>
                        <h3 class="mb-0" style="font-size: 2rem;"><span id="tanpaKeterangan-count">0</span></h3>
                    </div>
                </div>
            </div>
        </div>

    <!-- Tabel Default: Hadir -->
    <div id="table-container" class="p-3">
        <!-- Bagian ini diisi oleh data dari fungsi showTable -->
    </div>

    </div>

<script>
let selectedCard = 'hadir';

function showTable(status) {
    let tableContainer = document.getElementById('table-container');
    selectedCard = status;

        fetch('/administrator/getDashboardData')
            .then(response => response.json())
            .then(data => {
                // Mengisi jumlah karyawan pada setiap card
                document.querySelector('#hadir-count').textContent = data.hadir;
                document.querySelector('#sakit-count').textContent = data.sakit;
                document.querySelector('#cuti-count').textContent = data.cuti;
                document.querySelector('#tanpaKeterangan-count').textContent = data.tanpaKeterangan;
            })
            .catch(error => console.error('Error fetching data:', error));

    // Memulai permintaan AJAX
    fetch(`/administrator/getTableData/${status}`)
        .then((response) => response.json())
        .then((result) => {
            let tableHtml = `<h3 class="mt-3">Tabel ${capitalizeFirstLetter(status)}</h3>`;
            tableHtml += `<table class="table table-bordered table-striped text-center align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" width="10px">No.</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Jabatan</th>`;
            if (status === 'sakit' || status === 'cuti') {
                tableHtml += `<th scope="col">Judul</th>
                              <th scope="col">Deskripsi</th>
                              <th scope="col">Status</th>
                              <th scope="col">Waktu Pengajuan</th>`;
            } else if (status === 'tanpaKeterangan') {
                tableHtml += `<th scope="col">Email</th>
                              <th scope="col">No Telepon</th>`;
            }
            tableHtml += `</tr></thead><tbody>`;

            if (result.data.length > 0) {
                result.data.forEach((item, index) => {
                    tableHtml += `<tr>
                                    <th scope="row">${index + 1}</th>
                                    <td><a href="karyawan/detail/${item.kID}">${item.nama}</a></td>
                                    <td>${item.jabatan}</td>`;
                    if (status === 'sakit' || status === 'cuti') {
                        tableHtml += `<td>${item.judul}</td>
                                      <td>${item.deskripsi}</td>
                                      <td>${item.status}</td>
                                      <td>${item.waktu}</td>`;
                    } else if (status === 'tanpaKeterangan') {
                        tableHtml += `<td><a href="mailto:${item.email}">${item.email}</a></td>
                                      <td><a href="https://wa.me/${item.no_telepon}">${item.no_telepon}</a></td>`;
                    }
                    tableHtml += `</tr>`;
                });
            } else {
                tableHtml += `<tr><td colspan="8">Tidak ada data untuk status "${status}".</td></tr>`;
            }

            tableHtml += `</tbody></table>`;
            tableContainer.innerHTML = tableHtml;
        })
        .catch((error) => {
            tableContainer.innerHTML = `<p class="text-danger">Gagal memuat data: ${error.message}</p>`;
        });
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

// Tampilkan tabel "Hadir" secara default
showTable('hadir');


setInterval(function() {
    showTable(selectedCard);
}, 10000);

</script>

<?= $this->endSection('content') ?>