<?= $this->extend('template/navbarKaryawan'); ?>

<?= $this->section('content'); ?>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<div class="text-center">
    <h1>Scan QR Code</h1>
    <p>Arahkan ke barcode absensi pada komputer admin.</p>
    <div id="flash-message" style="display: none; color: red; text-align: center;"></div>
</div>
<div id="reader"></div>
<p id="result" style="text-align: center; font-size: 1.2em; margin-top: 20px; color: green;"></p>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const resultElement = document.getElementById("result");

    // Inisialisasi scanner menggunakan html5-qrcode
    const html5QrCode = new Html5Qrcode("reader");

    // Fungsi untuk memulai scanning
    function startScanning() {
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        };

        html5QrCode.start(
            { facingMode: "environment" }, // Kamera belakang
            config,
            qrCodeMessage => {
                resultElement.textContent = `Scan Berhasil. Mendaftarkan kehadiran Anda...`;
                html5QrCode.stop(); // Hentikan scanner setelah berhasil

                // Kirim kode QR ke server untuk memproses kehadiran
                $.ajax({
                    url: "/kehadiran/hadir",
                    type: "POST",
                    data: { code: qrCodeMessage },
                    dataType: "json",
                    success: function (response) {
                        if (response.status === "success") {
                            // Redirect ke halaman /home
                            window.location.href = "/home";
                        } else {
                            // Tampilkan flashdata atau pesan error
                            $("#flash-message").html(response.message).fadeIn().delay(3000).fadeOut();
                        }
                    },
                    error: function() {
                        console.error("Terjadi kesalahan saat memproses absensi.");
                    }
                });
            },
            errorMessage => {
                console.log(`QR Code not detected: ${errorMessage}`);
            }
        ).catch(err => {
            console.error("Camera start failed:", err);
        });
    }

    // Jalankan scanner saat halaman dimuat
    window.onload = startScanning;
</script>


<?= $this->endSection('content'); ?>
</body>

</html>