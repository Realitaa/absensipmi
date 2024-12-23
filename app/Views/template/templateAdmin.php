<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    body {
        margin: 20px;
        background-image: url(<?= base_url('WebBG.jpg'); ?>);
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        background-position: center;
    }
</style>

<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="https://www.pmimedan.or.id/wp-content/uploads/2021/02/logo-PMIMedan-e1618371991309.png" alt="Logo" height="24" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'dashboard') ? 'active' : ''; ?>" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'karyawan') ? 'active' : ''; ?>" href="/karyawan">Karyawan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'admin') ? 'active' : ''; ?>" href="/admin">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page === 'laporan') ? 'active' : ''; ?>" href="/laporan">Laporan</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="bg-light">
        <?php if (session()->getFlashdata('success')): ?>
            <div id="flash-message" class="alert alert-success mx-3">
                <?= session()->getFlashdata('success'); ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div id="flash-message" class="alert alert-danger m-3">
                <?= session()->getFlashdata('error'); ?>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
    // Menampilkan pesan dengan animasi
    window.addEventListener('DOMContentLoaded', (event) => {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                // Tambahkan kelas show untuk animasi masuk
                flashMessage.classList.add('show');

                // Hapus pesan setelah beberapa detik
                setTimeout(() => {
                    flashMessage.classList.remove('show');
                    setTimeout(() => flashMessage.remove(), 500); // Hapus elemen setelah animasi keluar
                }, 3000); // Durasi 3 detik
            }
        });
    </script>
</body>

</html>