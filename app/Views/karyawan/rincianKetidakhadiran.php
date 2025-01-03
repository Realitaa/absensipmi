<?= session('user_data')['Role'] == 'admin' ? $this->extend('template/navbarAdmin') : $this->extend('template/navbarKaryawan'); ?>
<?= $this->section('content'); ?>

<style>
    .sticky-bottom {
        position: sticky;
        bottom: 0;
        z-index: 1030; /* Atur z-index agar tidak tertutup konten lain */
    }
</style>

<div class="mx-5">
    <h2 class="text-center text-primary p-3">Formulir Ketidakhadiran</h2>
    <table>
        <tr>
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><a href="<?= session('user_data')['Role'] == 'karyawan' ? '/karyawan/' . esc($karyawan['id']) : '/administrator/karyawan/detail/' . esc($karyawan['id']) ?>"><?= esc($karyawan['nama']); ?></a></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td><?= esc($karyawan['jabatan']); ?></td>
            </tr>
            <td>Status</td>
            <td>:</td>
            <td>
            <?php if (esc($detail['status']) == 'Menunggu') : ?>
                <span class="text-primary">Menunggu<span id="dots"></span></span>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        function iterateDots() {
                            var el = document.getElementById("dots");
                            if (el) {
                                var dotsStr = el.innerHTML;
                                var dotsLen = dotsStr.length;

                                var maxDots = 3;
                                el.innerHTML = (dotsLen < maxDots ? dotsStr + '.' : '');
                                if (dotsLen >= maxDots) {
                                    el.innerHTML = ''; // Reset to empty after max dots
                                }
                            }
                        }

                        function startLoading() {
                            var intervalMs = 300; // Interval for dots animation
                            setInterval(iterateDots, intervalMs); // Call iterateDots at intervals
                        }

                        startLoading(); // Start dots animation
                    });
                </script>
            <?php elseif (esc($detail['status']) == 'Terima') : ?>
                <span class="text-success">Terima</span>
            <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Tipe</td>
            <td>:</td>
            <td><?= esc($durasi['tipe']); ?></td>
        </tr>
        <tr>
            <td>Mulai</td>
            <td>:</td>
            <td><?= esc($durasi['mulai']); ?></td>
        </tr>
        <tr>
            <td>Selesai</td>
            <td>:</td>
            <td><?= esc($durasi['selesai']); ?></td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
        </tr>
    </table>
    <?php if (!empty($detail['lampiran'])): ?>
        <?php
        $folderPath = FCPATH . '/lampiran/' . $detail['lampiran']; // Path absolut folder
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']); // Ambil semua file kecuali '.' dan '..'
        } else {
            $files = [];
        }
        ?>
        
        <?php if (!empty($files)): ?>
            <ul>
                <?php foreach ($files as $file): ?>
                    <li>
                        <a href="<?= base_url('/lampiran/' . $detail['lampiran'] . '/' . $file); ?>" target="_blank">
                            <?= htmlspecialchars($file, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Tidak ada lampiran yang ditemukan di folder ini.</p>
        <?php endif; ?>
    <?php endif; ?>

    <h4 class="text-center"><?= esc($detail['judul']); ?></h4>
    <p class="pb-2"><?= esc($detail['deskripsi']); ?></p>
</div>

<?php if (session('user_data')['Role'] == 'admin') : ?>
<!-- Elemen sticky -->
<div class="sticky-bottom bg-info border-top py-3">
        <div class="d-flex justify-content-between px-5">
            <form action="/absensipmi/administrator/ketidakhadiran/reject/<?= esc($absensi['id']); ?>" method="post">
                <input type="hidden" name="waktu" value="<?= esc($absensi['waktu']); ?>">
                <input type="hidden" name="tipe" value="<?= esc($durasi['tipe']); ?>">
                <button type="submit" class="btn btn-danger">Tolak</button>
            </form>
            <button type="button" class="btn btn-primary" 
                    id="popover-interaktif" 
                    data-bs-container="body" 
                    data-bs-toggle="popover" 
                    data-bs-placement="top" 
                    title="Hubungi Karyawan Ini">
                Hubungi Karyawan
            </button>
            <form action="/administrator/ketidakhadiran/accept/<?= esc($absensi['id']); ?>" method="post">
                <input type="hidden" name="waktu" value="<?= esc($absensi['waktu']); ?>">
                <input type="hidden" name="tipe" value="<?= esc($durasi['tipe']); ?>">
                <button type="submit" class="btn btn-success">Terima</button>
            </form>
        </div>
    </div>

    
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const popoverTriggerEl = document.getElementById('popover-interaktif');
        const popover = new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            content: `
                <div>
                    <p><strong>WhatsApp:</strong> <a href="https://wa.me/<?= esc($karyawan['no_telepon']); ?>" target="_blank"><?= esc($karyawan['no_telepon']); ?></a></p>
                    <p><strong>Email:</strong> <a href="mailto:<?= esc($karyawan['email']); ?>"><?= esc($karyawan['email']); ?></a></p>
                </div>
            `
        });
});
<?php endif; ?>
</script>

<?= $this->endSection('content'); ?>