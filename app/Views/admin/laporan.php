<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="bg-light">
    <div id="table-container" class="p-3">
        <h3 class="">Tabel Kehadiran Minggu Ini</h3>
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
        <h3 class="">Tabel Rekapitulasi Kehadiran Bulanan</h3>
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
            </tr>
        </tbody>
        </table>
    </div>
</div>

<?= $this->endSection('content'); ?>