<!-- File ini berisi modal untuk file editKaryawan.php dan editAdmin.php -->

<!-- Modal Nonaktifkan Karyawan-->
<div class="modal fade" id="nonactiveModal" tabindex="-1" aria-labelledby="nonactiveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
      <h1 class="modal-title fs-5" id="nonactiveModalLabel">
          <?= $isAdmin ? "Nonaktifkan Admin " . esc($admin['nama']) . "?" : "Nonaktifkan Karyawan " . esc($karyawan['nama']) . "?"; ?>
      </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda ingin menonaktifkan <?= $isAdmin ? "admin " . esc($admin['nama']) : "karyawan " . esc($karyawan['nama']); ?>?</p>
        <p><?= $isAdmin ? "Admin" : "Karyawan"; ?> yang dinonaktifkan tidak dapat mengakses fitur website.</p>
        <p>Anda dapat mengaktifkannya lagi nanti.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <?php if ($isAdmin) : ?>
          <form action="<?= base_url('/admin/status/' . $admin["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($admin['id']); ?>">
              <button type="submit" class="btn btn-warning" name="action" value="nonactive">Ya</button>
          </form>
        <?php else : ?>
          <form action="<?= base_url('/karyawan/status/' . $karyawan["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($karyawan['id']); ?>">
              <button type="submit" class="btn btn-warning" name="action" value="nonactive">Ya</button>
          </form>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Mengaktifkan Karyawan-->
<div class="modal fade" id="activeModal" tabindex="-1" aria-labelledby="activeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="activeModalLabel">
          <?= $isAdmin ? "Aktifkan Admin " . esc($admin['nama']) . "?" : "Aktifkan Karyawan " . esc($karyawan['nama']) . "?"; ?>
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda ingin mengaktifkan <?= $isAdmin ? "admin " . esc($admin['nama']) : "karyawan " . esc($karyawan['nama']); ?>?</p>
        <p><?= $isAdmin ? "Admin" : "Karyawan"; ?> dapat mengakses fitur website lagi setelah diaktifkan.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <?php if ($isAdmin) : ?>
          <form action="<?= base_url('admin/status/' . $admin["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($admin['id']); ?>">
              <button type="submit" class="btn btn-success" name="action" value="active">Ya</button>
          </form>
        <?php else : ?>
          <form action="<?= base_url('karyawan/status/' . $karyawan["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($karyawan['id']); ?>">
              <button type="submit" class="btn btn-success" name="action" value="active">Ya</button>
          </form>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>

<!-- Modal Menghapus Karyawan-->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteModalLabel">
          <?= $isAdmin ? "Menghapus Admin " . esc($admin['nama']) . "?" : "Menghapus Karyawan " . esc($karyawan['nama']) . "?"; ?>
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda ingin <span class="text-danger">menghapus</span> <?= $isAdmin ? "admin " . esc($admin['nama']) : "karyawan " . esc($karyawan['nama']); ?>?</p>
        <p>Tindakan ini tidak dapat dikembalikan dan <?= $isAdmin ? "admin" : "karyawan"; ?> akan dihapus secara permanen.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
        <?php if ($isAdmin) : ?>
          <form action="<?= base_url('admin/delete/' . $admin["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($admin['id']); ?>">
              <button type="submit" class="btn btn-danger">Hapus Admin</button>
          </form>
        <?php else : ?>
            <form action="<?= base_url('karyawan/delete/' . $karyawan["id"]) ?>" method="post">
              <input type="hidden" name="karyawan_id" value="<?= esc($karyawan['id']); ?>">
              <button type="submit" class="btn btn-danger">Hapus Karyawan</button>
          </form>
        <?php endif ?>
      </div>
    </div>
  </div>
</div>