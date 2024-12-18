<?= $this->include('karyawan/navbar') ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#form-izin').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    alert('Izin berhasil dikirim!');
                    window.location.href = '/karyawan';
                },
                error: function () {
                    alert('Gagal mengirim izin. Silakan coba lagi.');
                }
            });
        });
    });
</script>

<div class="container mt-4">
    <h2>Informasi Ketidakhadiran</h2>
    <p>Silakan isi form ini jika Anda tidak dapat hadir hari ini.</p>
    <form id="form-izin" action="/karyawan/kirimIzin" method="post">
        <div class="form-group">
            <label for="alasan">Alasan</label>
            <select class="form-control" id="alasan" name="alasan">
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        </div>
        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>