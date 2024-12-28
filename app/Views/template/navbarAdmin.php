<?php include 'header.php' ?>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/administrator/dashboard">
            <img src="https://www.pmimedan.or.id/wp-content/uploads/2021/02/logo-PMIMedan-e1618371991309.png" alt="Logo" height="24" class="d-inline-block align-text-top">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'dashboard') ? 'active' : ''; ?>" href="/administrator/dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'karyawan') ? 'active' : ''; ?>" href="/administrator/karyawan">Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'admin') ? 'active' : ''; ?>" href="/administrator/admin">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === 'laporan') ? 'active' : ''; ?>" href="/administrator/laporan">Laporan</a>
                </li>
            </ul>
            <div class="me-3 d-none d-lg-block">Waktu Server: <span class="txt"></span></div>
            <ul class="navbar-nav ms-auto d-lg-none">
                <div>Waktu Server: <span class="txt"></span></div>
                <!-- Profil di Navbar Collapse -->
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="/profile">
                        <img src="https://via.placeholder.com/30" alt="User Avatar" width="30" height="30" class="rounded-circle">
                        <span class="ms-2"><?= session('user_data')['Username']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout/admin"><span class="text-danger">Log Out</span></a>
                </li>
            </ul>
            <div class="dropdown d-none d-lg-block">
                <div id="txt"></div>
                <!-- Profil di Navbar untuk Desktop -->
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/30" alt="User Avatar" width="30" height="30" class="rounded-circle">
                    <span class="ms-2"><?= session('user_data')['Username']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <!-- <li><a class="dropdown-item" href="/admin/1">Profile</a></li> Ganti 1 dengan pengambilan id untuk setiap pengguna -->
                    <li>
                        <!-- <hr class="dropdown-divider"> -->
                    </li>
                    <li><a class="dropdown-item" href="/logout/admin"><span class="text-danger">Log Out</span></a></li>
                </ul>
            </div>
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
let serverTime = null;
let clientOffset = null;

// Mendapatkan waktu server dari API
function fetchServerTime() {
    fetch('/administrator/server-time')
        .then(response => response.json())
        .then(data => {
            serverTime = data.serverTime; // Waktu server dalam milidetik
            clientOffset = Date.now() - serverTime; // Hitung selisih waktu klien dan server
            startClock();
        })
        .catch(error => console.error('Error fetching server time:', error));
}

// Mulai menampilkan waktu real-time
function startClock() {
    setInterval(() => {
        if (serverTime !== null) {
            const currentServerTime = new Date(Date.now() - clientOffset);
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const dayName = days[currentServerTime.getDay()];
            const h = checkTime(currentServerTime.getHours());
            const m = checkTime(currentServerTime.getMinutes());
            const s = checkTime(currentServerTime.getSeconds());
            const timeString = `${dayName}, ${h}:${m}:${s}`;

            document.querySelectorAll('.txt').forEach(element => {
                element.innerHTML = timeString;
            });
        }
    }, 1000);
}

// Format angka kurang dari 10 dengan "0"
function checkTime(i) {
    return i < 10 ? "0" + i : i;
}

// Mulai sinkronisasi waktu
fetchServerTime();
</script>
</body>

</html>