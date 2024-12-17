<?= $this->extend('template/templateAdmin') ?>

<?= $this->section('content') ?>
    
    <div class="bg-light">
        <h2 class="ps-3">Selamat Datang Admin Admin1</h2>
        <p class="ps-3">Berikut ini adalah laporan singkat kehadiran Karyawan PMI Kota Medan hari ini.</p>
    

        <div class="container px-5">
        <div class="row justify-content-center">
            <!-- Card 1 -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="position-relative pt-3">
                        <img src="user.png" class="card-img-top" alt="Karyawan Hadir" style="width: 100px;">
                        <img src="check.png" class="position-absolute" alt="Check" style="bottom: -20px; right: -10px; width: 65px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Hadir</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="position-relative pt-3">
                        <img src="thermometer.png" class="card-img-top" alt="Karyawan Sakit" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Sakit</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="position-relative pt-3">
                        <img src="beach-umbrella.png" class="card-img-top" alt="Karyawan Izin" style="width: 100px;">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h2>Izin</h2>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-6 col-sm-6 col-md-3 mb-4">
                <div class="card align-items-center" style="border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                    <div class="position-relative">
                        <i class="bi bi-question-lg text-danger" style="font-size: 80px;"></i>
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <h4>Tanpa Keterangan</h4>
                        <h3 class="mb-0" style="font-size: 2rem;">0</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>


<?= $this->endSection('content') ?>