<?= $this->extend('template/templateAdmin') ?>

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
    
    <div class="bg-light">
        <h2 class="ps-3">Selamat Datang Admin Admin1</h2>
        <p class="ps-3">Berikut ini adalah laporan singkat kehadiran Karyawan PMI Kota Medan hari ini.</p>
    

        <div class="container px-5">
        <div class="row justify-content-center">
            <!-- Card Hadir -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('hadir')">
                    <div class="position-relative pt-3">
                        <img src="user.png" class="card-img-top" alt="Karyawan Hadir" style="width: 100px;">
                        <img src="check.png" class="position-absolute" alt="Check" style="bottom: -20px; right: -10px; width: 65px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Hadir</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card Sakit -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('sakit')">
                    <div class="position-relative pt-3">
                        <img src="thermometer.png" class="card-img-top" alt="Karyawan Sakit" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Sakit</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card Izin -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('izin')">
                    <div class="position-relative pt-3">
                        <img src="beach-umbrella.png" class="card-img-top" alt="Karyawan Izin" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Izin</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card Tanpa Keterangan -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" onclick="showTable('tanpaKeterangan')">
                    <div class="position-relative">
                        <i class="bi bi-question-lg text-danger" style="font-size: 80px;"></i>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4>Tanpa Keterangan</h4>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Default: Hadir -->
    <div id="table-container" class="p-3">
        <h3 class="mt-3">Tabel Kehadiran</h3>
        <table class="table table-bordered table-striped">
        <thead>
            <tr>
            <th scope="col" width="10px">No.</th>
            <th scope="col">Nama</th>
            <th scope="col">Divisi</th>
            <th scope="col">Waktu</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <th scope="row">1</th>
            <td>Mark</td>
            <td>Otto</td>
            <td>@mdo</td>
            </tr>
            <tr>
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>Thornton</td>
            <td>@fat</td>
            </tr>
        </tbody>
        </table>
    </div>

    </div>

<script>
// Fungsi untuk menampilkan tabel sesuai card yang diklik
function showTable(status) {
        let tableContainer = document.getElementById('table-container');
        let tableHtml = '';

        // Menentukan tabel yang sesuai dengan status
        if (status === 'hadir') {
            tableHtml = `
                <h3 class="mt-3">Tabel Kehadiran</h3>
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th scope="col" width="10px">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                </tbody>
                </table>
            `;
        } else if (status === 'sakit') {
            tableHtml = `
                <h3 class="mt-3">Tabel Sakit</h3>
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th scope="col" width="10px">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                </tbody>
                </table>
            `;
        } else if (status === 'izin') {
            tableHtml = `
                <h3 class="mt-3">Tabel Izin</h3>
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th scope="col" width="10px">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                </tbody>
                </table>
            `;
        } else if (status === 'tanpaKeterangan') {
            tableHtml = `
                <h3 class="mt-3">Tabel Tanpa Keterangan</h3>
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                    <th scope="col" width="10px">No.</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Divisi</th>
                    <th scope="col">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                    </tr>
                </tbody>
                </table>
            `;
        }

        // Menampilkan tabel yang sesuai
        tableContainer.innerHTML = tableHtml;
    }

    // Menampilkan tabel Hadir secara default
    showTable('hadir');
</script>

<?= $this->endSection('content') ?>