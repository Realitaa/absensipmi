<?php include 'header.php' ?>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/home">
            <img src="https://www.pmimedan.or.id/wp-content/uploads/2021/02/logo-PMIMedan-e1618371991309.png" alt="Logo" height="24" class="d-inline-block align-text-top">
        </a>
            <ul class="navbar-nav me-auto">
                
            </ul>
            <ul class="navbar-nav ms-auto d-lg-none">
            </ul>
            <!-- Atur logika untuk mengecek apakah akun sudah secure (sudah di atur password nya) -->
            <i class="bi bi-exclamation-triangle me-2 text-danger blink-icon" 
            style="font-size: 20px;"
            data-bs-toggle="popover" 
            data-bs-html="true" 
            data-bs-content="Password belum di tentukan. Tentukan password kamu di pengaturan!"></i>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/30" alt="User Avatar" width="30" height="30" class="rounded-circle">
                    <span class="ms-2">Username</span> <!-- Ganti dengan username pengguna yang diambil dari session -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/karyawan/1">Profile</a></li> <!-- Ganti 1 dengan pengambilan id untuk setiap pengguna -->
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout"><span class="text-danger">Log Out</span></a></li>
                </ul>
            </div>
    </div>
</nav>
<div class="bg-light">
    <?php include 'alertMessage.php' ?>
    <?= $this->renderSection('content') ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="<?= base_url('/template.js'); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const icon = document.querySelector('.blink-icon');
        const popover = new bootstrap.Popover(icon, {
            trigger: 'manual', // Popover dikontrol manual
            placement: 'top',  // Letak popover
            html: true         // Izinkan HTML di dalam popover
        });

        let isPopoverVisible = false; // Status popover

        // Toggle popover saat elemen diklik
        icon.addEventListener('click', function () {
            if (isPopoverVisible) {
                popover.hide();
            } else {
                popover.show();
            }
            isPopoverVisible = !isPopoverVisible;
        });

        // Sembunyikan popover jika pengguna mengklik di luar elemen
        document.addEventListener('click', function (e) {
            if (isPopoverVisible && !e.target.closest('.blink-icon') && !e.target.closest('.popover')) {
                popover.hide();
                isPopoverVisible = false;
            }
        });
        let isDanger = true;

        setInterval(() => {
            if (isDanger) {
                icon.classList.remove('text-danger');
                icon.classList.add('text-light');
            } else {
                icon.classList.remove('text-light');
                icon.classList.add('text-danger');
            }
            isDanger = !isDanger;
        }, 1000); // Ubah warna setiap 1 detik
    });
    </script>
</body>
</html>