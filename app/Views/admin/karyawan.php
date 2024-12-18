<?= $this->extend('template/templateAdmin'); ?>

<?= $this->section('content'); ?>

<div class="bg-light">
    <div id="table-container" class="p-3">
        <h3 class="text-center">Tabel Karyawan</h3>
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

<?= $this->endSection('content'); ?>