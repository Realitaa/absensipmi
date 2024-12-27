<?= view('template/header'); ?>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/administrator/dashboard">
            <img src="https://www.pmimedan.or.id/wp-content/uploads/2021/02/logo-PMIMedan-e1618371991309.png" alt="Logo" height="24" class="d-inline-block align-text-top">
        </a>
        <div>
            <h2 id="timer" class="pe-5"></h2>
        </div>
    </div>
</nav>
<div class="bg-light">
    <div class="container py-4">
        <div class="card mx-auto p-4">
            <div class="card-body">
                
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <h1 class="h3 mb-3">Scan Barcode untuk Mengisi Kehadiran Anda</h1>
                        <p class="text-muted">Ikuti langkah-langkah berikut untuk mengisi absensi Anda</p>
                        <ol class="list-group list-group-flush list-group-numbered mb-4">
                            <li class="list-group-item">Buka <a href="<?= base_url(); ?>"><?= base_url(); ?></a> di browser Anda.</li>
                            <li class="list-group-item">Login ke akun Anda.</li>
                            <li class="list-group-item">Klik tombol <span class="text-primary">Mulai Absensi</span> (tersedia setelah mengatur password akun Anda).</li>
                            <li class="list-group-item">Arahkan telepon Anda di layar ini untuk memindai kode QR</li>
                        </ol>
                    </div>
                    <div class="col-md-5 text-center">
                    <img id="qr-code" src="" alt="QR Code" style="width: 300px; height: 300px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center pb-3 logs" id="log-container">
        <p><i>Memuat log...</i></p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let qrCodeFailed = false; // Flag untuk memeriksa error QR Code

// Fungsi untuk memuat log
function fetchLogs() {
    if (qrCodeFailed) {
        // Jika QR Code gagal dimuat, log tidak akan diambil hingga berhasil
        return;
    }

    $.ajax({
        url: '<?= base_url('/administrator/absensi/getAttendanceLogs') ?>',
        method: 'GET',
        success: function (response) {
            if (response.logs) {
                updateLog(response.logs); // Pastikan memanggil hanya bagian `logs`
            } else {
                addToLog("Tidak ada data log yang tersedia.");
            }
        },
        error: function () {
            addToLog("Gagal memuat log dari database. Coba lagi nanti.");
        }
    });
}

function updateLog(logs) {
    const logContainer = $('#log-container');
    logContainer.empty(); // Kosongkan kontainer log

    logs.forEach(log => {
        logContainer.append(`<p><i>${log.nama} melakukan absensi pada pukul ${log.waktu}</i></p>`);
    });
}

// Fungsi untuk menambahkan pesan ke log
function addToLog(message) {
    const logContainer = $('#log-container');
    const logItems = logContainer.children();

    // Tambahkan pesan baru ke log
    logContainer.prepend(`<p><i>${message}</i></p>`);

    // Hapus log lama jika melebihi 3 baris
    if (logItems.length >= 3) {
        logContainer.children().last().remove();
    }
}

// Fungsi untuk memeriksa QR Code
function fetchQRCode() {
    $.ajax({
        url: '<?= base_url('/administrator/absensi/fetchqr') ?>',
        method: 'GET',
        success: function (response) {
            qrCodeFailed = false; // Reset flag jika berhasil
            $('#qr-code').attr('src', response.qrCodeURL); // Perbarui QR Code
            resetTimer(); // Reset timer setiap kali QR Code berhasil dimuat
        },
        error: function () {
            qrCodeFailed = true; // Set flag jika QR Code gagal
            addToLog("<span class='text-danger'>Failed to fetch QR Code. Please wait for the next QR Code.</span>");
        }
    });
}

let countdown = 10; // detik
let timerId;

// Fungsi untuk mereset dan memperbarui timer
function resetTimer() {
    countdown = 10; // Reset countdown ke 5 detik
    const timerElement = document.getElementById('timer');
    timerElement.textContent = countdown.toString();
    clearInterval(timerId); // Hentikan interval lama
    timerId = setInterval(updateTimer, 1000); // Mulai interval baru
}

// Fungsi untuk memperbarui timer
function updateTimer() {
    const timerElement = document.getElementById('timer');
    if (countdown > 0) {
        timerElement.textContent = countdown.toString();
        countdown--;
    } else {
        clearInterval(timerId);
        timerElement.textContent = 'Refreshing QR Code...';
    }
}

// Inisialisasi
fetchQRCode();

// Jalankan polling
setInterval(fetchQRCode, 12000); // Perbarui QR Code setiap 12 detik
setInterval(fetchLogs, 3000); // Perbarui log setiap 3 detik

    
</script>
</body>
</html>