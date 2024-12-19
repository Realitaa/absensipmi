<?= $this->extend('template/template'); ?>
<?= $this->section('content'); ?>
<?= $this->include('karyawan/navbar') ?>


<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>


<div class="container mt-4">
    <h2>Permohonan Cuti</h2>
    <p>Silakan isi form ini untuk mengajukan cuti.</p>
    <form action="/karyawan/kirimCuti" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="waktu">Waktu Cuti</label>
            <input type="date" class="form-control" id="waktu" name="waktu">
        </div>
        <div class="form-group">
            <label for="alasan">Alasan</label>
            <textarea class="form-control" id="alasan" name="alasan" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="lampiran">Lampiran</label>
            <input type="file" class="form-control" id="lampiran" name="lampiran" onchange="previewFile()">
            <small class="form-text text-muted">Hanya file PNG, JPG, PDF (max 2MB).</small>
            <div id="preview" class="mt-3"></div>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>

<script>
    function previewFile() {
        const file = document.getElementById('lampiran').files[0];
        const preview = document.getElementById('preview');
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = `<embed src="${e.target.result}" width="100%" height="200px" />`;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    }
</script>

<?= $this->endSection('content'); ?>