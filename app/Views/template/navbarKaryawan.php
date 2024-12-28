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
            <?php if (!session('user_data')['isSecureAccount']): ?>
                <script src="/popoverNavbar.js"></script>
                <i class="bi bi-exclamation-triangle me-2 text-danger blink-icon" 
                style="font-size: 20px;"
                data-bs-toggle="popover" 
                data-bs-html="true" 
                data-bs-content="Password belum di tentukan. Tentukan password kamu di profile!"></i>
            <?php endif ?>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= !empty(session('user_data')['Foto']) ? '/userProfile/' . session('user_data')['Foto'] : '/user.png' ; ?>" alt="User Avatar" width="30" height="30" class="rounded-circle">
                    <span class="ms-2"><?= session('user_data')['Username']; ?></span> <!-- Ganti dengan username pengguna yang diambil dari session -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="/karyawan/me">Profile</a></li> <!-- Ganti 1 dengan pengambilan id untuk setiap pengguna -->
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/logout/user"><span class="text-danger">Log Out</span></a></li>
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
</body>
</html>